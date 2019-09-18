) use ($stream) {
            if ('hello' === $data) {
                fclose($stream);
            }
        });
        $p->wait();

        $this->assertSame('hello', $p->getOutput());
    }

    /**
     * @expectedException \Symfony\Component\Process\Exception\LogicException
     * @expectedExceptionMessage Input can not be set while the process is running.
     */
    public function testSetInputWhileRunningThrowsAnException()
    {
        $process = $this->getProcessForCode('sleep(30);');
        $process->start();
        try {
            $process->setInput('foobar');
            $process->stop();
            $this->fail('A LogicException should have been raised.');
        } catch (LogicException $e) {
        }
        $process->stop();

        throw $e;
    }

    /**
     * @dataProvider provideInvalidInputValues
     * @expectedException \Symfony\Component\Process\Exception\InvalidArgumentException
     * @expectedExceptionMessage Symfony\Component\Process\Process::setInput only accepts strings, Traversable objects or stream resources.
     */
    public function testInvalidInput($value)
    {
        $process = $this->getProcess('foo');
        $process->setInput($value);
    }

    public function provideInvalidInputValues()
    {
        return [
            [[]],
            [new NonStringifiable()],
        ];
    }

    /**
     * @dataProvider provideInputValues
     */
    public function testValidInput($expected, $value)
    {
        $process = $this->getProcess('foo');
        $process->setInput($value);
        $this->assertSame($expected, $process->getInput());
    }

    public function provideInputValues()
    {
        return [
            [null, null],
            ['24.5', 24.5],
            ['input data', 'input data'],
        ];
    }

    public function chainedCommandsOutputProvider()
    {
        if ('\\' === \DIRECTORY_SEPARATOR) {
            return [
                ["2 \r\n2\r\n", '&&', '2'],
            ];
        }

        return [
            ["1\n1\n", ';', '1'],
            ["2\n2\n", '&&', '2'],
        ];
    }

    /**
     * @dataProvider chainedCommandsOutputProvider
     */
    public function testChainedCommandsOutput($expected, $operator, $input)
    {
        $process = $this->getProcess(sprintf('echo %s %s echo %s', $input, $operator, $input));
        $process->run();
        $this->assertEquals($expected, $process->getOutput());
    }

    public function testCallbackIsExecutedForOutput()
    {
        $p = $this->getProcessForCode('echo \'foo\';');

        $called = false;
        $p->run(function ($type, $buffer) use (&$called) {
            $called = 'foo' === $buffer;
        });

        $this->assertTrue($called, 'The callback should be executed with the output');
    }

    public function testCallbackIsExecutedForOutputWheneverOutputIsDisabled()
    {
        $p = $this->getProcessForCode('echo \'foo\';');
        $p->disableOutput();

        $called = false;
        $p->run(function ($type, $buffer) use (&$called) {
            $called = 'foo' === $buffer;
        });

        $this->assertTrue($called, 'The callback should be executed with the output');
    }

    public function testGetErrorOutput()
    {
        $p = $this->getProcessForCode('$n = 0; while ($n < 3) { file_put_contents(\'php://stderr\', \'ERROR\'); $n++; }');

        $p->run();
        $this->assertEquals(3, preg_match_all('/ERROR/', $p->getErrorOutput(), $matches));
    }

    public function testFlushErrorOutput()
    {
        $p = $this->getProcessForCode('$n = 0; while ($n < 3) { file_put_contents(\'php://stderr\', \'ERROR\'); $n++; }');

        $p->run();
        $p->clearErrorOutput();
        $this->assertEmpty($p->getErrorOutput());
    }

    /**
     * @dataProvider provideIncrementalOutput
     */
    public function testIncrementalOutput($getOutput, $getIncrementalOutput, $uri)
    {
        $lock = tempnam(sys_get_temp_dir(), __FUNCTION__);

        $p = $this->getProcessForCode('file_put_contents($s = \''.$uri.'\', \'foo\'); flock(fopen('.var_export($lock, true).', \'r\'), LOCK_EX); file_put_contents($s, \'bar\');');

        $h = fopen($lock, 'w');
        flock($h, LOCK_EX);

        $p->start();

        foreach (['foo', 'bar'] as $s) {
            while (false === strpos($p->$getOutput(), $s)) {
                usleep(1000);
            }

            $this->assertSame($s, $p->$getIncrementalOutput());
            $this->assertSame('', $p->$getIncrementalOutput());

            flock($h, LOCK_UN);
        }

        fclose($h);
    }

    public function provideIncrementalOutput()
    {
        return [
            ['getOutput', 'getIncrementalOutput', 'php://stdout'],
            ['getErrorOutput', 'getIncrementalErrorOutput', 'php://stderr'],
        ];
    }

    public function testGetOutput()
    {
        $p = $this->getProcessForCode('$n = 0; while ($n < 3) { echo \' foo \'; $n++; }');

        $p->run();
        $this->assertEquals(3, preg_match_all('/foo/', $p->getOutput(), $matches));
    }

    public function testFlushOutput()
    {
        $p = $this->getProcessForCode('$n=0;while ($n<3) {echo \' foo \';$n++;}');

        $p->run();
        $p->clearOutput();
        $this->assertEmpty($p->getOutput());
    }

    public function testZeroAsOutput()
    {
        if ('\\' === \DIRECTORY_SEPARATOR) {
            // see http://stackoverflow.com/questions/7105433/windows-batch-echo-without-new-line
            $p = $this->getProcess('echo | set /p dummyName=0');
        } else {
            $p = $this->getProcess('printf 0');
        }

        $p->run();
        $this->assertSame('0', $p->getOutput());
    }

    public function testExitCodeCommandFailed()
    {
        if ('\\' === \DIRECTORY_SEPARATOR) {
            $this->markTestSkipped('Windows does not support POSIX exit code');
        }

        // such command run in bash return an exitcode 127
        $process = $this->getProcess('nonexistingcommandIhopeneversomeonewouldnameacommandlikethis');
        $process->run();

        $this->assertGreaterThan(0, $process->getExitCode());
    }

    public function testTTYCommand()
    {
        if ('\\' === \DIRECTORY_SEPARATOR) {
            $this->markTestSkipped('Windows does not have /dev/tty support');
        }

        $process = $this->getProcess('echo "foo" >> /dev/null && '.$this->getProcessForCode('usleep(100000);')->getCommandLine());
        $process->setTty(true);
        $process->start();
        $this->assertTrue($process->isRunning());
        $process->wait();

        $this->assertSame(Process::STATUS_TERMINATED, $process->getStatus());
    }

    public function testTTYCommandExitCode()
    {
        if ('\\' === \DIRECTORY_SEPARATOR) {
            $this->markTestSkipped('Windows does have /dev/tty support');
        }

        $process = $this->getProcess('echo "foo" >> /dev/null');
        $process->setTty(true);
        $process->run();

        $this->assertTrue($process->isSuccessful());
    }

    /**
     * @expectedException \Symfony\Component\Process\Exception\RuntimeException
     * @expectedExceptionMessage TTY mode is not supported on Windows platform.
     */
    public function testTTYInWindowsEnvironment()
    {
        if ('\\' !== \DIRECTORY_SEPARATOR) {
            $this->markTestSkipped('This test is for Windows platform only');
        }

        $process = $this->getProcess('echo "foo" >> /dev/null');
        $process->setTty(false);
        $process->setTty(true);
    }

    public function testExitCodeTextIsNullWhenExitCodeIsNull()
    {
        $process = $this->getProcess('');
        $this->assertNull($process->getExitCodeText());
    }

    public function testPTYCommand()
    {
        if (!Process::isPtySupported()) {
            $this->markTestSkipped('PTY is not supported on this operating system.');
        }

        $process = $this->getProcess('echo "foo"');
        $process->setPty(true);
        $process->run();

        $this->assertSame(Process::STATUS_TERMINATED, $process->getStatus());
        $this->assertEquals("foo\r\n", $process->getOutput());
    }

    public function testMustRun()
    {
        $process = $this->getProcess('echo foo');

        $this->assertSame($process, $process->mustRun());
        $this->assertEquals('foo'.PHP_EOL, $process->getOutput());
    }

    public function testSuccessfulMustRunHasCorrectExitCode()
    {
        $process = $this->getProcess('echo foo')->mustRun();
        $this->assertEquals(0, $process->getExitCode());
    }

    /**
     * @expectedException \Symfony\Component\Process\Exception\ProcessFailedException
     */
    public function testMustRunThrowsException()
    {
        $process = $this->getProcess('exit 1');
        $process->mustRun();
    }

    public function testExitCodeText()
    {
        $process = $this->getProcess('');
        $r = new \ReflectionObject($process);
        $p = $r->getProperty('exitcode');
        $p->setAccessible(true);

        $p->setValue($process, 2);
        $this->assertEquals('Misuse of shell builtins', $process->getExitCodeText());
    }

    public function testStartIsNonBlocking()
    {
        $process = $this->getProcessForCode('usleep(500000);');
        $start = microtime(true);
        $process->start();
        $end = microtime(true);
        $this->assertLessThan(0.4, $end - $start);
        $process->stop();
    }

    public function testUpdateStatus()
    {
        $process = $this->getProcess('echo foo');
        $process->run();
        $this->assertGreaterThan(0, \strlen($process->getOutput()));
    }

    public function testGetExitCodeIsNullOnStart()
    {
        $process = $this->getProcessForCode('usleep(100000);');
        $this->assertNull($process->getExitCode());
        $process->start();
        $this->assertNull($process->getExitCode());
        $process->wait();
        $this->assertEquals(0, $process->getExitCode());
    }

    public function testGetExitCodeIsNullOnWhenStartingAgain()
    {
        $process = $this->getProcessForCode('usleep(100000);');
        $process->run();
        $this->assertEquals(0, $process->getExitCode());
        $process->start();
        $this->assertNull($process->getExitCode());
        $process->wait();
        $this->assertEquals(0, $process->getExitCode());
    }

    public function testGetExitCode()
    {
        $process = $this->getProcess('echo foo');
        $process->run();
        $this->assertSame(0, $process->getExitCode());
    }

    public function testStatus()
    {
        $process = $this->getProcessForCode('usleep(100000);');
        $this->assertFalse($process->isRunning());
        $this->assertFalse($process->isStarted());
        $this->assertFalse($process->isTerminated());
        $this->assertSame(Process::STATUS_READY, $process->getStatus());
        $process->start();
        $this->assertTrue($process->isRunning());
        $this->assertTrue($process->isStarted());
        $this->assertFalse($process->isTerminated());
        $this->assertSame(Process::STATUS_STARTED, $process->getStatus());
        $process->wait();
        $this->assertFalse($process->isRunning());
        $this->assertTrue($process->isStarted());
        $this->assertTrue($process->isTerminated());
        $this->assertSame(Process::STATUS_TERMINATED, $process->getStatus());
    }

    public function testStop()
    {
        $process = $this->getProcessForCode('sleep(31);');
        $process->start();
        $this->assertTrue($process->isRunning());
        $process->stop();
        $this->assertFalse($process->isRunning());
    }

    public function testIsSuccessful()
    {
        $process = $this->getProcess('echo foo');
        $process->run();
        $this->assertTrue($process->isSuccessful());
    }

    public function testIsSuccessfulOnlyAfterTerminated()
    {
        $process = $this->getProcessForCode('usleep(100000);');
        $process->start();

        $this->assertFalse($process->isSuccessful());

        $process->wait();

        $this->assertTrue($process->isSuccessful());
    }

    public function testIsNotSuccessful()
    {
        $process = $this->getProcessForCode('throw new \Exception(\'BOUM\');');
        $process->run();
        $this->assertFalse($process->isSuccessful());
    }

    public function testProcessIsNotSignaled()
    {
        if ('\\' === \DIRECTORY_SEPARATOR) {
            $this->markTestSkipped('Windows does not support POSIX signals');
        }

        $process = $this->getProcess('echo foo');
        $process->run();
        $this->assertFalse($process->hasBeenSignaled());
    }

    public function testProcessWithoutTermSignal()
    {
        if ('\\' === \DIRECTORY_SEPARATOR) {
            $this->markTestSkipped('Windows does not support POSIX signals');
        }

        $process = $this->getProcess('echo foo');
        $process->run();
        $this->assertEquals(0, $process->getTermSignal());
    }

    public function testProcessIsSignaledIfStopped()
    {
        if ('\\' === \DIRECTORY_SEPARATOR) {
            $this->markTestSkipped('Windows does not support POSIX signals');
        }

        $process = $this->getProcessForCode('sleep(32);');
        $process->start();
        $process->stop();
        $this->assertTrue($process->hasBeenSignaled());
        $this->assertEquals(15, $process->getTermSignal()); // SIGTERM
    }

    /**
     * @expectedException \Symfony\Component\Process\Exception\ProcessSignaledException
     * @expectedExceptionMessage The process has been signaled with signal "9".
     */
    public function testProcessThrowsExceptionWhenExternallySignaled()
    {
        if (!\function_exists('posix_kill')) {
            $this->markTestSkipped('Function posix_kill is required.');
        }

        if (self::$sigchild) {
            $this->markTestSkipped('PHP is compiled with --enable-sigchild.');
        }

        $process = $this->getProcessForCode('sleep(32.1);');
        $process->start();
        posix_kill($process->getPid(), 9); // SIGKILL

        $process->wait();
    }

    public function testRestart()
    {
        $process1 = $this->getProcessForCode('echo getmypid();');
        $process1->run();
        $process2 = $process1->restart();

        $process2->wait(); // wait for output

        // Ensure that both processed finished and the output is numeric
        $this->assertFalse($process1->isRunning());
        $this->assertFalse($process2->isRunning());
        $this->assertInternalType('numeric', $process1->getOutput());
        $this->assertInternalType('numeric', $process2->getOutput());

        // Ensure that restart returned a new process by check that the output is different
        $this->assertNotEquals($process1->getOutput(), $process2->getOutput());
    }

    /**
     * @expectedException \Symfony\Component\Process\Exception\ProcessTimedOutException
     * @expectedExceptionMessage exceeded the timeout of 0.1 seconds.
     */
    public function testRunProcessWithTimeout()
    {
        $process = $this->getProcessForCode('sleep(30);');
        $process->setTimeout(0.1);
        $start = microtime(true);
        try {
            $process->run();
            $this->fail('A RuntimeException should have been raised');
        } catch (RuntimeException $e) {
        }

        $this->assertLessThan(15, microtime(true) - $start);

        throw $e;
    }

    /**
     * @expectedException \Symfony\Component\Process\Exception\ProcessTimedOutException
     * @expectedExceptionMessage exceeded the timeout of 0.1 seconds.
     */
    public function testIterateOverProcessWithTimeout()
    {
        $process = $this->getProcessForCode('sleep(30);');
        $process->setTimeout(0.1);
        $start = microtime(true);
        try {
            $process->start();
            foreach ($process as $buffer);
            $this->fail('A RuntimeException should have been raised');
        } catch (RuntimeException $e) {
        }

        $this->assertLessThan(15, microtime(true) - $start);

        throw $e;
    }

    public function testCheckTimeoutOnNonStartedProcess()
    {
        $process = $this->getProcess('echo foo');
        $this->assertNull($process->checkTimeout());
    }

    public function testCheckTimeoutOnTerminatedProcess()
    {
        $process = $this->getProcess('echo foo');
        $process->run();
        $this->assertNull($process->checkTimeout());
    }

    /**
     * @expectedException \Symfony\Component\Process\Exception\ProcessTimedOutException
     * @expectedExceptionMessage exceeded the timeout of 0.1 seconds.
     */
    public function testCheckTimeoutOnStartedProcess()
    {
        $process = $this->getProcessForCode('sleep(33);');
        $process->setTimeout(0.1);

        $process->start();
        $start = microtime(true);

        try {
            while ($process->isRunning()) {
                $process->checkTimeout();
                usleep(100000);
            }
            $this->fail('A ProcessTimedOutException should have been raised');
        } catch (ProcessTimedOutException $e) {
        }

        $this->assertLessThan(15, microtime(true) - $start);

        throw $e;
    }

    public function testIdleTimeout()
    {
        $process = $this->getProcessForCode('sleep(34);');
        $process->setTimeout(60);
        $process->setIdleTimeout(0.1);

        try {
            $process->run();

            $this->fail('A timeout exception was expected.');
        } catch (ProcessTimedOutException $e) {
            $this->assertTrue($e->isIdleTimeout());
            $this->assertFalse($e->isGeneralTimeout());
            $this->assertEquals(0.1, $e->getExceededTimeout());
        }
    }

    public function testIdleTimeoutNotExceededWhenOutputIsSent()
    {
        $process = $this->getProcessForCode('while (true) {echo \'foo \'; usleep(1000);}');
        $process->setTimeout(1);
        $process->start();

        while (false === strpos($process->getOutput(), 'foo')) {
            usleep(1000);
        }

        $process->setIdleTimeout(0.5);

        try {
            $process->wait();
            $this->fail('A timeout exception was expected.');
        } catch (ProcessTimedOutException $e) {
            $this->assertTrue($e->isGeneralTimeout(), 'A general timeout is expected.');
            $this->assertFalse($e->isIdleTimeout(), 'No idle timeout is expected.');
            $this->assertEquals(1, $e->getExceededTimeout());
        }
    }

    /**
     * @expectedException \Symfony\Component\Process\Exception\ProcessTimedOutException
     * @expectedExceptionMessage exceeded the timeout of 0.1 seconds.
     */
    public function testStartAfterATimeout()
    {
        $process = $this->getProcessForCode('sleep(35);');
        $process->setTimeout(0.1);

        try {
            $process->run();
            $this->fail('A ProcessTimedOutE