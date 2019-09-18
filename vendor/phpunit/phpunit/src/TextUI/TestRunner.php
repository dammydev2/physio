<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util\Log;

use DOMDocument;
use DOMElement;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExceptionWrapper;
use PHPUnit\Framework\SelfDescribing;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestFailure;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;
use PHPUnit\Util\Filter;
use PHPUnit\Util\Printer;
use PHPUnit\Util\Xml;
use ReflectionClass;
use ReflectionException;

/**
 * A TestListener that generates a logfile of the test execution in XML markup.
 *
 * The XML markup used is the same as the one that is used by the JUnit Ant task.
 */
class JUnit extends Printer implements TestListener
{
    /**
     * @var DOMDocument
     */
    protected $document;

    /**
     * @var DOMElement
     */
    protected $root;

    /**
     * @var bool
     */
    protected $reportUselessTests = false;

    /**
     * @var bool
     */
    protected $writeDocument = true;

    /**
     * @var DOMElement[]
     */
    protected $testSuites = [];

    /**
     * @var int[]
     */
    protected $testSuiteTests = [0];

    /**
     * @var int[]
     */
    protected $testSuiteAssertions = [0];

    /**
     * @var int[]
     */
    protected $testSuiteErrors = [0];

    /**
     * @var int[]
     */
    protected $testSuiteFailures = [0];

    /**
     * @var int[]
     */
    protected $testSuiteSkipped = [0];

    /**
     * @var int[]
     */
    protected $testSuiteTimes = [0];

    /**
     * @var int
     */
    protected $testSuiteLevel = 0;

    /**
     * @var DOMElement
     */
    protected $currentTestCase;

    /**
     * Constructor.
     *
     * @param null|mixed $out
     *
     * @throws \PHPUnit\Framework\Exception
     */
    public function __construct($out = null, bool $reportUselessTests = false)
    {
        $this->document               = new DOMDocument('1.0', 'UTF-8');
        $this->document->formatOutput = true;

        $this->root = $this->document->createElement('testsuites');
        $this->document->appendChild($this->root);

        parent::__construct($out);

        $this->reportUselessTests = $reportUselessTests;
    }

    /**
     * Flush buffer and close output.
     */
    public function flush(): void
    {
        if ($this->writeDocument === true) {
            $this->write($this->getXML());
        }

        parent::flush();
    }

    /**
     * An error occurred.
     *
     * @throws \InvalidArgumentException
     */
    public function addError(Test $test, \Throwable $t, float $time): void
    {
        $this->doAddFault($test, $t, $time, 'error');
        $this->testSuiteErrors[$this->testSuiteLevel]++;
    }

    /**
     * A warning occurred.
     *
     * @throws \InvalidArgumentException
     */
    public function addWarning(Test $test, Warning $e, float $time): void
    {
        $this->doAddFault($test, $e, $time, 'warning');
        $this->testSuiteFailures[$this->testSuiteLevel]++;
    }

    /**
     * A failure occurred.
     *
     * @throws \InvalidArgumentException
     */
    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        $this->doAddFault($test, $e, $time, 'failure');
        $this->testSuiteFailures[$this->testSuiteLevel]++;
    }

    /**
     * Incomplete test.
     */
    public function addIncompleteTest(Test $test, \Throwable $t, float $time): void
    {
        $this->doAddSkipped($test);
    }

    /**
     * Risky test.
     */
    public function addRiskyTest(Test $test, \Throwable $t, float $time): void
    {
        if (!$this->reportUselessTests || $this->currentTestCase === null) {
            return;
        }

        $error = $this->document->createElement(
            'error',
            Xml::prepareString(
                "Risky Test\n" .
                Filter::getFilteredStacktrace($t)
            )
        );

        $error->setAttribute('type', \get_class($t));

        $this->currentTestCase->appendChild($error);

        $this->testSuiteErrors[$this->testSuiteLevel]++;
    }

    /**
     * Skipped test.
     */
    public function addSkippedTest(Test $test, \Throwable $t, float $time): void
    {
        $this->doAddSkipped($test);
    }

    /**
     * A testsuite started.
     */
    public function startTestSuite(TestSuite $suite): void
    {
        $testSuite = $this->document->createElement('testsuite');
        $testSuite->setAttribute('name', $suite->getName());

        if (\class_exists($suite->getName(), false)) {
            try {
                $class = new ReflectionClass($suite->getName());

                $testSuite->setAttribute('file', $class->getFileName());
            } catch (ReflectionException $e) {
            }
        }

        if ($this->testSuiteLevel > 0) {
            $this->testSuites[$this->testSuiteLevel]->appendChild($testSuite);
        } else {
            $this->root->appendChild($testSuite);
        }

        $this->testSuiteLevel++;
        $this->testSuites[$this->testSuiteLevel]          = $testSuite;
        $this->testSuiteTests[$this->testSuiteLevel]      = 0;
        $this->testSuiteAssertions[$this->testSuiteLevel] = 0;
        $this->testSuiteErrors[$this->testSuiteLevel]     = 0;
        $this->testSuiteFailures[$this->testSuiteLevel]   = 0;
        $this->testSuiteSkipped[$this->testSuiteLevel]    = 0;
        $this->testSuiteTimes[$this->testSuiteLevel]      = 0;
    }

    /**
     * A testsuite ended.
     */
    public function endTestSuite(TestSuite $suite): void
    {
        $this->testSuites[$this->testSuiteLevel]->setAttribute(
            'tests',
            $this->testSuiteTests[$this->testSuiteLevel]
        );

        $this->testSuites[$this->testSuiteLevel]->setAttribute(
            'assertions',
            $this->testSuiteAssertions[$this->testSuiteLevel]
        );

        $this->testSuites[$this->testSuiteLevel]->setAttribute(
            'errors',
            $this->testSuiteErrors[$this->testSuiteLevel]
        );

        $this->testSuites[$this->testSuiteLevel]->setAttribute(
            'failures',
            $this->testSuiteFailures[$this->testSuiteLevel]
        );

        $this->testSuites[$this->testSuiteLevel]->setAttribute(
            'skipped',
            $this->testSuiteSkipped[$this->testSuiteLevel]
        );

        $this->testSuites[$this->testSuiteLevel]->setAttribute(
            'time',
            \sprintf('%F', $this->testSuiteTimes[$this->testSuiteLevel])
        );

        if ($this->testSuiteLevel > 1) {
            $this->testSuiteTests[$this->testSuiteLevel - 1] += $this->testSuiteTests[$this->testSuiteLevel];
            $this->testSuiteAssertions[$this->testSuiteLevel - 1] += $this->testSuiteAssertions[$this->testSuiteLevel];
            $this->testSuiteErrors[$this->testSuiteLevel - 1] += $this->testSuiteErrors[$this->testSuiteLevel];
            $this->testSuiteFailures[$this->testSuiteLevel - 1] += $this->testSuiteFailures[$this->testSuiteLevel];
            $this->testSuiteSkipped[$this->testSuiteLevel - 1] += $this->testSuiteSkipped[$this->testSuiteLevel];
            $this->testSuiteTimes[$this->testSuiteLevel - 1] += $this->testSuiteTimes[$this->testSuiteLevel];
        }

        $this->testSuiteLevel--;
    }

    /**
     * A test started.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ReflectionException
     */
    public function startTest(Test $test): void
    {
        $usesDataprovider = false;

        if (\method_exists($test, 'usesDataProvider')) {
            $usesDataprovider = $test->usesDataProvider();
        }

        $testCase = $this->document->createElement('testcase');
        $testCase->setAttribute('name', $test->getName());

        $class      = new ReflectionClass($test);
        $methodName = $test->getName(!$usesDataprovider);

        if ($class->hasMethod($methodName)) {
            $method = $class->getMethod($methodName);

            $testCase->setAttribute('class', $class->getName());
            $testCase->setAttribute('classname', \str_replace('\\', '.', $class->getName()));
            $testCase->setAttribute('file', $class->getFileName());
            $testCase->setAttribute('line', $method->getStartLine());
        }

        $this->currentTestCase = $testCase;
    }

    /**
     * A test ended.
     */
    public function endTest(Test $test, float $time): void
    {
        $numAssertions = 0;

        if (\method_exists($test, 'getNumAssertions')) {
            $numAssertions = $test->getNumAssertions();
        }

        $this->testSuiteAssertions[$this->testSuiteLevel] += $numAssertions;

        $this->currentTestCase->setAttribute(
            'assertions',
            $numAssertions
        );

        $this->currentTestCase->setAttribute(
            'time',
            \sprintf('%F', $time)
        );

        $this->testSuites[$this->testSuiteLevel]->appendChild(
            $this->currentTestCase
        );

        $this->testSuiteTests[$this->testSuiteLevel]++;
        $this->testSuiteTimes[$this->testSuiteLevel] += $time;

        $testOutput = '';

        if (\method_exists($test, 'hasOutput') && \method_exists($test, 'getActualOutput')) {
            $testOutput = $test->hasOutput() ? $test->getActualOutput() : '';
        }

        if (!empty($testOutput)) {
            $systemOut = $this->document->createElement(
                'system-out',
                Xml::prepareString($testOutput)
            );

            $this->currentTestCase->appendChild($systemOut);
        }

        $this->currentTestCase = null;
    }

    /**
     * Returns the XML as a string.
     */
    public function getXML(): string
    {
        return $this->document->saveXML();
    }

    /**
     * Enables or disables the writing of the document
     * in flush().
     *
     * This is a "hack" needed for the integration of
     * PHPUnit with Phing.
     */
    public function setWriteDocument(/*bool*/ $flag): void
    {
        if (\is_bool($flag)) {
            $this->writeDocument = $flag;
        }
    }

    /**
     * Method which generalizes addError() and addFailure()
     *
     * @throws \InvalidArgumentException
     */
    private function doAddFault(Test $test, \Throwable $t, float $time, $type): void
    {
        if ($this->currentTestCase === null) {
            return;
        }

        if ($test instanceof SelfDescribing) {
            $buffer = $test->toString() . "\n";
        } else {
            $buffer = '';
        }

        $buffer .= TestFailure::exceptionToString($t) . "\n" .
                   Filter::getFilteredStacktrace($t);

        $fault = $this->document->createElement(
            $type,
            Xml::prepareString($buffer)
        );

        if ($t instanceof ExceptionWrapper) {
            $fault->setAttribute('type', $t->getClassName());
        } else {
            $fault->setAttribute('type', \get_class($t));
        }

        $this->currentTestCase->appendChild($fault);
    }

    private function doAddSkipped(Test $test): void
    {
        if ($this->currentTestCase === null) {
            return;
        }

        $skipped = $this->document->createElement('skipped');
        $this->currentTestCase->appendChild($skipped);

        $this->testSuiteSkipped[$this->testSuiteLevel]++;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util\Log;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExceptionWrapper;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestFailure;
use PHPUnit\Framework\TestResult;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;
use PHPUnit\TextUI\ResultPrinter;
use PHPUnit\Util\Filter;
use ReflectionClass;
use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * A TestListener that generates a logfile of the test execution using the
 * TeamCity format (for use with PhpStorm, for instance).
 */
class TeamCity extends ResultPrinter
{
    /**
     * @var bool
     */
    private $isSummaryTestCountPrinted = false;

    /**
     * @var string
     */
    private $startedTestName;

    /**
     * @var false|int
     */
    private $flowId;

    public function printResult(TestResult $result): void
    {
        $this->printHeader();
        $this->printFooter($result);
    }

    /**
     * An error occurred.
     *
     * @throws \InvalidArgumentException
     */
    public function addError(Test $test, \Throwable $t, float $time): void
    {
        $this->printEvent(
            'testFailed',
            [
                'name'     => $test->getName(),
                'message'  => self::getMessage($t),
                'details'  => self::getDetails($t),
                'duration' => self::toMilliseconds($time),
            ]
        );
    }

    /**
     * A warning occurred.
     *
     * @throws \InvalidArgumentException
     */
    public function addWarning(Test $test, Warning $e, float $time): void
    {
        $this->printEvent(
            'testFailed',
            [
                'name'     => $test->getName(),
                'message'  => self::getMessage($e),
                'details'  => self::getDetails($e),
                'duration' => self::toMilliseconds($time),
            ]
        );
    }

    /**
     * A failure occurred.
     *
     * @throws \InvalidArgumentException
     */
    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        $parameters = [
            'name'     => $test->getName(),
            'message'  => self::getMessage($e),
            'details'  => self::getDetails($e),
            'duration' => self::toMilliseconds($time),
        ];

        if ($e instanceof ExpectationFailedException) {
            $comparisonFailure = $e->getComparisonFailure();

            if ($comparisonFailure instanceof ComparisonFailure) {
                $expectedString = $comparisonFailure->getExpectedAsString();

                if ($expectedString === null || empty($expectedString)) {
                    $expectedString = self::getPrimitiveValueAsString($comparisonFailure->getExpected());
                }

                $actualString = $comparisonFailure->getActualAsString();

                if ($actualString === null || empty($actualString)) {
                    $actualString = self::getPrimitiveValueAsString($comparisonFailure->getActual());
                }

                if ($actualString !== null && $expectedString !== null) {
                    $parameters['type']     = 'comparisonFailure';
                    $parameters['actual']   = $actualString;
                    $parameters['expected'] = $expectedString;
                }
            }
        }

        $this->printEvent('testFailed', $parameters);
    }

    /**
     * Incomplete test.
     */
    public function addIncompleteTest(Test $test, \Throwable $t, float $time): void
    {
        $this->printIgnoredTest($test->getName(), $t, $time);
    }

    /**
     * Risky test.
     *
     * @throws \InvalidArgumentException
     */
    public function addRiskyTest(Test $test, \Throwable $t, float $time): void
    {
        $this->addError($test, $t, $time);
    }

    /**
     * Skipped test.
     *
     * @throws \ReflectionException
     */
    public function addSkippedTest(Test $test, \Throwable $t, float $time): void
    {
        $testName = $test->getName();

        if ($this->startedTestName !== $testName) {
            $this->startTest($test);
            $this->printIgnoredTest($testName, $t, $time);
            $this->endTest($test, $time);
        } else {
            $this->printIgnoredTest($testName, $t, $time);
        }
    }

    public function printIgnoredTest($testName, \Throwable $t, float $time): void
    {
        $this->printEvent(
            'testIgnored',
            [
                'name'     => $testName,
                'message'  => self::getMessage($t),
                'details'  => self::getDetails($t),
                'duration' => self::toMilliseconds($time),
            ]
        );
    }

    /**
     * A testsuite started.
     *
     * @throws \ReflectionException
     */
    public function startTestSuite(TestSuite $suite): void
    {
        if (\stripos(\ini_get('disable_functions'), 'getmypid') === false) {
            $this->flowId = \getmypid();
        } else {
            $this->flowId = false;
        }

        if (!$this->isSummaryTestCountPrinted) {
            $this->isSummaryTestCountPrinted = true;

            $this->printEvent(
                'testCount',
                ['count' => \count($suite)]
            );
        }

        $suiteName = $suite->getName();

        if (empty($suiteName)) {
            return;
        }

        $parameters = ['name' => $suiteName];

        if (\class_exists($suiteName, false)) {
            $fileName                   = self::getFileName($suiteName);
            $parameters['locationHint'] = "php_qn://$fileName::\\$suiteName";
        } else {
            $split = \explode('::', $suiteName);

            if (\count($split) === 2 && \method_exists($split[0], $split[1])) {
                $fileName                   = self::getFileName($split[0]);
                $parameters['locationHint'] = "php_qn://$fileName::\\$suiteName";
                $parameters['name']         = $split[1];
            }
        }

        $this->printEvent('testSuiteStarted', $parameters);
    }

    /**
     * A testsuite ended.
     */
    public function endTestSuite(TestSuite $suite): void
    {
        $suiteName = $suite->getName();

        if (empty($suiteName)) {
            return;
        }

        $parameters = ['name' => $suiteName];

        if (!\class_exists($suiteName, false)) {
            $split = \explode('::', $suiteName);

            if (\count($split) === 2 && \method_exists($split[0], $split[1])) {
                $parameters['name'] = $split[1];
            }
        }

        $this->printEvent('testSuiteFinished', $parameters);
    }

    /**
     * A test started.
     *
     * @throws \ReflectionException
     */
    public function startTest(Test $test): void
    {
        $testName              = $test->getName();
        $this->startedTestName = $testName;
        $params                = ['name' => $testName];

        if ($test instanceof TestCase) {
            $className              = \get_class($test);
            $fileName               = self::getFileName($className);
            $params['locationHint'] = "php_qn://$fileName::\\$className::$testName";
        }

        $this->printEvent('testStarted', $params);
    }

    /**
     * A test ended.
     */
    public function endTest(Test $test, float $time): void
    {
        parent::endTest($test, $time);

        $this->printEvent(
            'testFinished',
            [
                'name'     => $test->getName(),
                'duration' => self::toMilliseconds($time),
            ]
        );
    }

    protected function writeProgress(string $progress): void
    {
    }

    /**
     * @param string $eventName
     * @param array  $params
     */
    private function printEvent($eventName, $params = []): void
    {
        $this->write("\n##teamcity[$eventName");

        if ($this->flowId) {
            $params['flowId'] = $this->flowId;
        }

        foreach ($params as $key => $value) {
            $escapedValue = self::escapeValue($value);
            $this->write(" $key='$escapedValue'");
        }

        $this->write("]\n");
    }

    private static function getMessage(\Throwable $t): string
    {
        $message = '';

        if ($t instanceof ExceptionWrapper) {
            if ($t->getClassName() !== '') {
                $message .= $t->getClassName();
            }

            if ($message !== '' && $t->getMessage() !== '') {
                $message .= ' : ';
            }
        }

        return $message . $t->getMessage();
    }

    /**
     * @throws \InvalidArgumentException
     */
    private static function getDetails(\Throwable $t): string
    {
        $stackTrace = Filter::getFilteredStacktrace($t);
        $previous   = $t instanceof ExceptionWrapper ? $t->getPreviousWrapped() : $t->getPrevious();

        while ($previous) {
            $stackTrace .= "\nCaused by\n" .
                TestFailure::exceptionToString($previous) . "\n" .
                Filter::getFilteredStacktrace($previous);

            $previous = $previous instanceof ExceptionWrapper ?
                $previous->getPreviousWrapped() : $previous->getPrevious();
        }

        return ' ' . \str_replace("\n", "\n ", $stackTrace);
    }

    private static function getPrimitiveValueAsString($value): ?string
    {
        if ($value === null) {
            return 'null';
        }

        if (\is_bool($value)) {
            return $value === true ? 'true' : 'false';
        }

        if (\is_scalar($value)) {
            return \print_r($value, true);
        }

        return null;
    }

    private static function escapeValue(string $text): string
    {
        return \str_replace(
            ['|', "'", "\n", "\r", ']', '['],
            ['||', "|'", '|n', '|r', '|]', '|['],
            $text
        );
    }

    /**
     * @param string $className
     *
     * @throws \ReflectionException
     */
    private static function getFileName($className): string
    {
        $reflectionClass = new ReflectionClass($className);

        return $reflectionClass->getFileName();
    }

    /**
     * @param float $time microseconds
     */
    private static function toMilliseconds(float $time): int
    {
        return \round($time * 1000);
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           <?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util\PHP;

use __PHP_Incomplete_Class;
use ErrorException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\SyntheticError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestFailure;
use PHPUnit\Framework\TestResult;
use SebastianBergmann\Environment\Runtime;

/**
 * Utility methods for PHP sub-processes.
 */
abstract class AbstractPhpProcess
{
    /**
     * @var Runtime
     */
    protected $runtime;

    /**
     * @var bool
     */
    protected $stderrRedirection = false;

    /**
     * @var string
     */
    protected $stdin = '';

    /**
     * @var string
     */
    protected $args = '';

    /**
     * @var array<string, string>
     */
    protected $env = [];

    /**
     * @var int
     */
    protected $timeout = 0;

    public static function factory(): self
    {
        if (\DIRECTORY_SEPARATOR === '\\') {
            return new WindowsPhpProcess;
        }

        return new DefaultPhpProcess;
    }

    public function __construct()
    {
        $this->runtime = new Runtime;
    }

    /**
     * Defines if should use STDERR redirection or not.
     *
     * Then $stderrRedirection is TRUE, STDERR is redirected to STDOUT.
     */
    public function setUseStderrRedirection(bool $stderrRedirection): void
    {
        $this->stderrRedirection = $stderrRedirection;
    }

    /**
     * Returns TRUE if uses STDERR redirection or FALSE if not.
     */
    public function useStderrRedirection(): bool
    {
        return $this->stderrRedirection;
    }

    /**
     * Sets the input string to be sent via STDIN
     */
    public function setStdin(string $stdin): void
    {
        $this->stdin = $stdin;
    }

    /**
     * Returns the input string to be sent via STDIN
     */
    public function getStdin(): string
    {
        return $this->stdin;
    }

    /**
     * Sets the string of arguments to pass to the php job
     */
    public function setArgs(string $args): void
    {
        $this->args = $args;
    }

    /**
     * Returns the string of arguments to pass to the php job
     */
    public function getArgs(): string
    {
        return $this->args;
    }

    /**
     * Sets the array of environment variables to start the child process with
     *
     * @param array<string, string> $env
     */
    public function setEnv(array $env): void
    {
        $this->env = $env;
    }

    /**
     * Returns the array of environment variables to start the child process with
     */
    public function getEnv(): array
    {
        return $this->env;
    }

    /**
     * Sets the amount of seconds to wait before timing out
     */
    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }

    /**
     * Returns the amount of seconds to wait before timing out
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Runs a single test in a separate PHP process.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function runTestJob(string $job, Test $test, TestResult $result): void
    {
        $result->startTest($test);

        $_result = $this->runJob($job);

        $this->processChildResult(
            $test,
            $result,
            $_result['stdout'],
            $_result['stderr']
        );
    }

    /**
     * Returns the command based into the configurations.
     */
    public function getCommand(array $settings, string $file = null): string
    {
        $command = $this->runtime->getBinary();
        $command .= $this->settingsToParameters($settings);

        if (\PHP_SAPI === 'phpdbg') {
            $command .= ' -qrr';

            if (!$file) {
                $command .= 's=';
            }
        }

        if ($file) {
            $command .= ' ' . \escapeshellarg($file);
        }

        if ($this->args) {
            if (!$file) {
                $command .= ' --';
            }
            $command .= ' ' . $this->args;
        }

        if ($this->stderrRedirection === true) {
            $command .= ' 2>&1';
        }

        return $command;
    }

    /**
     * Runs a single job (PHP code) using a separate PHP process.
     */
    abstract public function runJob(string $job, array $settings = []): array;

    protected function settingsToParameters(array $settings): string
    {
        $buffer = '';

        foreach ($settings as $setting) {
            $buffer .= ' -d ' . \escapeshellarg($setting);
        }

        return $buffer;
    }

    /**
     * Processes the TestResult object from an isolated process.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    private function processChildResult(Test $test, TestResult $result, string $stdout, string $stderr): void
    {
        $time = 0;

        if (!empty($stderr)) {
            $result->addError(
                $test,
                new Exception(\trim($stderr)),
                $time
            );
        } else {
            \set_error_handler(function ($errno, $errstr, $errfile, $errline): void {
                throw new ErrorException($errstr, $errno, $errno, $errfile, $errline);
            });

            try {
                if (\strpos($stdout, "#!/usr/bin/env php\n") === 0) {
                    $stdout = \substr($stdout, 19);
                }

                $childResult = \unserialize(\str_replace("#!/usr/bin/env php\n", '', $stdout));
                \restore_error_handler();
            } catch (ErrorException $e) {
                \restore_error_handler();
                $childResult = false;

                $result->addError(
                    $test,
                    new Exception(\trim($stdout), 0, $e),
                    $time
                );
            }

            if ($childResult !== false) {
                if (!empty($childResult['output'])) {
                    $output = $childResult['output'];
                }

                /* @var TestCase $test */

                $test->setResult($childResult['testResult']);
                $test->addToAssertionCount($childResult['numAssertions']);

                /** @var TestResult $childResult */
                $childResult = $childResult['result'];

                if ($result->getCollectCodeCoverageInformation()) {
                    $result->getCodeCoverage()->merge(
                        $childResult->getCodeCoverage()
                    );
                }

                $time           = $childResult->time();
                $notImplemented = $childResult->notImplemented();
                $risky          = $childResult->risky();
                $skipped        = $childResult->skipped();
                $errors         = $childResult->errors();
                $warnings       = $childResult->warnings();
                $failures       = $childResult->failures();

                if (!empty($notImplemented)) {
                    $result->addError(
                        $test,
                        $this->getException($notImplemented[0]),
                        $time
                    );
                } elseif (!empty($risky)) {
                    $result->addError(
                        $test,
                        $this->getException($risky[0]),
                        $time
                    );
                } elseif (!empty($skipped)) {
                    $result->addError(
                        $test,
                        $this->getException($skipped[0]),
                        $time
                    );
                } elseif (!empty($errors)) {
                    $result->addError(
                        $test,
                        $this->getException($errors[0]),
                        $time
                    );
                } elseif (!empty($warnings)) {
                    $result->addWarning(
                        $test,
                        $this->getException($warnings[0]),
                        $time
                    );
                } elseif (!empty($failures)) {
                    $result->addFailure(
                        $test,
                        $this->getException($failures[0]),
                        $time
                    );
                }
            }
        }

        $result->endTest($test, $time);

        if (!empty($output)) {
            print $output;
        }
    }

    /**
     * Gets the thrown exception from a PHPUnit\Framework\TestFailure.
     *
     * @see https://github.com/sebastianbergmann/phpunit/issues/74
     */
    private function getException(TestFailure $error): Exception
    {
        $exception = $error->thrownException();

        if ($exception instanceof __PHP_Incomplete_Class) {
            $exceptionArray = [];

            foreach ((array) $exception as $key => $value) {
                $key                  = \substr($key, \strrpos($key, "\0") + 1);
                $exceptionArray[$key] = $value;
            }

            $exception = new SyntheticError(
                \sprintf(
                    '%s: %s',
                    $exceptionArray['_PHP_Incomplete_Class_Name'],
                    $exceptionArray['message']
                ),
                $exceptionArray['code'],
                $exceptionArray['file'],
                $exceptionArray['line'],
                $exceptionArray['trace']
            );
        }

        return $exception;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         <?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util\PHP;

use PHPUnit\Framework\Exception;

/**
 * Default utility for PHP sub-processes.
 */
class DefaultPhpProcess extends AbstractPhpProcess
{
    /**
     * @var string
     */
    protected $tempFile;

    /**
     * Runs a single job (PHP code) using a separate PHP process.
     *
     * @throws Exception
     */
    public function runJob(string $job, array $settings = []): array
    {
        if ($this->useTemporaryFile() || $this->stdin) {
            if (!($this->tempFile = \tempnam(\sys_get_temp_dir(), 'PHPUnit')) ||
                \file_put_contents($this->tempFile, $job) === false) {
                throw new Exception(
                    'Unable to write temporary file'
                );
            }

            $job = $this->stdin;
        }

        return $this->runProcess($job, $settings);
    }

    /**
     * Returns an array of file handles to be used in place of pipes
     */
    protected function getHandles(): array
    {
        return [];
    }

    /**
     * Handles creating the child process and returning the STDOUT and STDERR
     *
     * @throws Exception
     */
    protected function runProcess(string $job, array $settings): array
    {
        $handles = $this->getHandles();

        $env = null;

        if ($this->env) {
            $env = $_SERVER ?? [];
            unset($env['argv'], $env['argc']);
            $env = \array_merge($env, $this->env);

            foreach ($env as $envKey => $envVar) {
                if (\is_array($envVar)) {
                    unset($env[$envKey]);
                }
            }
        }

        $pipeSpec = [
            0 => $handles[0] ?? ['pipe', 'r'],
            1 => $handles[1] ?? ['pipe', 'w'],
            2 => $handles[2] ?? ['pipe', 'w'],
        ];

        $process = \proc_open(
            $this->getCommand($settings, $this->tempFile),
            $pipeSpec,
            $pipes,
            null,
            $env
        );

        if (!\is_resource($process)) {
            throw new Exception(
                'Unable to spawn worker process'
            );
        }

        if ($job) {
            $this->process($pipes[0], $job);
        }

        \fclose($pipes[0]);

        $stderr = $stdout = '';

        if ($this->timeout) {
            unset($pipes[0]);

            while (true) {
                $r = $pipes;
                $w = null;
                $e = null;

                $n = @\stream_select($r, $w, $e, $this->timeout);

                if ($n === false) {
                    break;
                }

                if ($n === 0) {
                    \proc_terminate($process, 9);

                    throw new Exception(
                        \sprintf(
                            'Job execution aborted after %d seconds',
                            $this->timeout
                        )
                    );
                }

                if ($n > 0) {
                    foreach ($r as $pipe) {
                        $pipeOffset = 0;

                        foreach ($pipes as $i => $origPipe) {
                            if ($pipe === $origPipe) {
                                $pipeOffset = $i;

                                break;
                            }
                        }

                        if (!$pipeOffset) {
                            break;
                        }

                        $line = \fread($pipe, 8192);

                        if ($line === '') {
                            \fclose($pipes[$pipeOffset]);

                            unset($pipes[$pipeOffset]);
                        } else {
                            if ($pipeOffset === 1) {
                                $stdout .= $line;
                            } else {
                                $stderr .= $line;
                            }
                        }
                    }

                    if (empty($pipes)) {
                        break;
                    }
                }
            }
        } else {
            if (isset($pipes[1])) {
                $stdout = \stream_get_contents($pipes[1]);

                \fclose($pipes[1]);
            }

            if (isset($pipes[2])) {
                $stderr = \stream_get_contents($pipes[2]);

                \fclose($pipes[2]);
            }
        }

        if (isset($handles[1])) {
            \rewind($handles[1]);

            $stdout = \stream_get_contents($handles[1]);

            \fclose($handles[1]);
        }

        if (isset($handles[2])) {
            \rewind($handles[2]);

            $stderr = \stream_get_contents($handles[2]);

            \fclose($handles[2]);
        }

        \proc_close($process);

        $this->cleanup();

        return ['stdout' => $stdout, 'stderr' => $stderr];
    }

    protected function process($pipe, string $job): void
    {
        \fwrite($pipe, $job);
    }

    protected function cleanup(): void
    {
        if ($this->tempFile) {
            \unlink($this->tempFile);
        }
    }

    protected function useTemporaryFile(): bool
    {
        return false;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 <?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util\PHP;

use PHPUnit\Framework\Exception;

/**
 * Windows utility for PHP sub-processes.
 *
 * Reading from STDOUT or STDERR hangs forever on Windows if the output is
 * too large.
 *
 * @see https://bugs.php.net/bug.php?id=51800
 */
class WindowsPhpProcess extends DefaultPhpProcess
{
    public function getCommand(array $settings, string $file = null): string
    {
        return '"' . parent::getCommand($settings, $file) . '"';
    }

    protected function getHandles(): array
    {
        if (false === $stdout_handle = \tmpfile()) {
            throw new Exception(
                'A temporary file could not be created; verify that your TEMP environment variable is writable'
            );
        }

        return [
            1 => $stdout_handle,
        ];
    }

    protected function useTemporaryFile(): bool
    {
        return true;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <?php
use SebastianBergmann\CodeCoverage\CodeCoverage;

$composerAutoload = {composerAutoload};
$phar             = {phar};

ob_start();

$GLOBALS['__PHPUNIT_ISOLATION_BLACKLIST'][] = '{job}';

if ($composerAutoload) {
    require_once $composerAutoload;
    define('PHPUNIT_COMPOSER_INSTALL', $composerAutoload);
} else if ($phar) {
    require $phar;
}

{globals}
$coverage = null;

if (isset($GLOBALS['__PHPUNIT_BOOTSTRAP'])) {
    require_once $GLOBALS['__PHPUNIT_BOOTSTRAP'];
}

if (class_exists('SebastianBergmann\CodeCoverage\CodeCoverage')) {
    $coverage =	new CodeCoverage(null);
    $coverage->start(__FILE__);
}

register_shutdown_function(function() use ($coverage) {
    $output = null;
    if ($coverage) {
        $output = $coverage->stop();
    }
    file_put_contents('{coverageFile}', serialize($output));
});

ob_end_clean();

require '{job}';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <?php
use SebastianBergmann\CodeCoverage\CodeCoverage;

if (!defined('STDOUT')) {
    // php://stdout does not obey output buffering. Any output would break
    //