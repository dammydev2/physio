<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Process\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\InputStream;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Pipes\PipesInterface;
use Symfony\Component\Process\Process;

/**
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class ProcessTest extends TestCase
{
    private static $phpBin;
    private static $process;
    private static $sigchild;

    public static function setUpBeforeClass()
    {
        $phpBin = new PhpExecutableFinder();
        self::$phpBin = getenv('SYMFONY_PROCESS_PHP_TEST_BINARY') ?: ('phpdbg' === \PHP_SAPI ? 'php' : $phpBin->find());

        ob_start();
        phpinfo(INFO_GENERAL);
        self::$sigchild = false !== strpos(ob_get_clean(), '--enable-sigchild');
    }

    protected function tearDown()
    {
        if (self::$process) {
            self::$process->stop(0);
            self::$process = null;
        }
    }

    /**
     * @expectedException \Symfony\Component\Process\Exception\RuntimeException
     * @expectedExceptionMessage The provided cwd does not exist.
     */
    public function testInvalidCwd()
    {
        try {
            // Check that it works fine if the CWD exists
            $cmd = new Process(['echo', 'test'], __DIR__);
            $cmd->run();
        } catch (\Exception $e) {
            $this->fail($e);
        }

        $cmd = new Process(['echo', 'test'], __DIR__.'/notfound/');
        $cmd->run();
    }

    public function testThatProcessDoesNotThrowWarningDuringRun()
    {
        if ('\\' === \DIRECTORY_SEPARATOR) {
            $this->markTestSkipped('This test is transient on Windows');
        }
        @trigger_error('Test Error', E_USER_NOTICE);
        $process = $this->getProcessForCode('sleep(3)');
        $process->run();
        $actualError = error_get_last();
        $this->assertEquals('Test Error', $actualError['message']);
        $this->assertEquals(E_USER_NOTICE, $actualError['type']);
    }

    /**
     * @expectedException \Symfony\Component\Process\Exception\InvalidArgumentException
     */
    public function testNegativeTimeoutFromConstructor()
    {
        $this->getProcess('', null, null, null, -1);
    }

    /**
     * @expectedException \Symfony\Component\Process\Exception\InvalidArgumentException
     */
    public function testNegativeTimeoutFromSetter()
    {
        $p = $this->getProcess('');
        $p->setTimeout(-1);
    }

    public function testFloatAndNullTimeout()
    {
        $p = $this->getProcess('');

        $p->setTimeout(10);
        $this->assertSame(10.0, $p->getTimeout());

        $p->setTimeout(null);
        $this->assertNull($p->getTimeout());

        $p->setTimeout(0.0);
        $this->assertNull($p->getTimeout());
    }

    /**
     * @requires extension pcntl
     */
    public function testStopWithTimeoutIsActuallyWorking()
    {
        $p = $this->getProcess([self::$phpBin, __DIR__.'/NonStopableProcess.php', 30]);
        $p->start();

        while ($p->isRunning() && false === strpos($p->getOutput(), 'received')) {
            usleep(1000);
        }

        if (!$p->isRunning()) {
            throw new \LogicException('Process is not running: '.$p->getErrorOutput());
        }

        $start = microtime(true);
        $p->stop(0.1);

        $p->wait();

        $this->assertLessThan(15, microtime(true) - $start);
    }

    public function testWaitUntilSpecificOutput()
    {
        if ('\\' === \DIRECTORY_SEPARATOR) {
            $this->markTestIncomplete('This test is too transient on Windows, help wanted to improve it');
        }

        $p = $this->getProcess([self::$phpBin, __DIR__.'/KillableProcessWithOutput.php']);
        $p->start();

        $start = microtime(true);

        $completeOutput = '';
        $result = $p->waitUntil(function ($type, $output) use (&$completeOutput) {
            return false !== strpos($completeOutput .= $output, 'One more');
        });
        $this->assertTrue($result);
        $this->assertLessThan(20, microtime(true) - $start);
        $this->assertStringStartsWith("First iteration output\nSecond iteration output\nOne more", $completeOutput);
        $p->stop();
    }

    public function testWaitUntilCanReturnFalse()
    {
        $p = $this->getProcess('echo foo');
        $p->start();
        $this->assertFalse($p->waitUntil(function () { return false; }));
    }

    public function testAllOutputIsActuallyReadOnTermination()
    {
        // this code will result in a maximum of 2 reads of 8192 bytes by calling
        // start() and isRunning().  by the time getOutput() is called the process
        // has terminated so the internal pipes array is already empty. normally
        // the call to start() will not read any data as the process will not have
        // generated output, but this is non-deterministic so we must count it as
        // a possibility.  therefore we need 2 * PipesInterface::CHUNK_SIZE plus
        // another byte which will never be read.
        $expectedOutputSize = PipesInterface::CHUNK_SIZE * 2 + 2;

        $code = sprintf('echo str_repeat(\'*\', %d);', $expectedOutputSize);
        $p = $this->getProcessForCode($code);

        $p->start();

        // Don't call Process::run nor Process::wait to avoid any read of pipes
        $h = new \ReflectionProperty($p, 'process');
        $h->setAccessible(true);
        $h = $h->getValue($p);
        $s = @proc_get_status($h);

        while (!empty($s['running'])) {
            usleep(1000);
            $s = proc_get_status($h);
        }

        $o = $p->getOutput();

        $this->assertEquals($expectedOutputSize, \strlen($o));
    }

    public function testCallbacksAreExecutedWithStart()
    {
        $proc