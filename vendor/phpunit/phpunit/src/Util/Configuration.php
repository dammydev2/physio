this->bufferExecutionOrder) {
            $this->bufferTestResult($test, $resultMessage);
            $this->flushOutputBuffer();
        } else {
            $this->writeTestResult($resultMessage);

            if ($this->lastTestFailed) {
                $this->bufferTestResult($test, $resultMessage);
            }
        }

        parent::endTest($test, $time);
    }

    public function addError(Test $test, \Throwable $t, float $time): void
    {
        $this->lastTestFailed    = true;
        $this->testResultMessage = $this->formatTestResultMessage(
            $this->formatWithColor('fg-yellow', '‚úò'),
            (string) $t,
            $time,
            true
        );
    }

    public function addWarning(Test $test, Warning $e, float $time): void
    {
        $this->lastTestFailed    = true;
        $this->testResultMessage = $this->formatTestResultMessage(
            $this->formatWithColor('fg-yellow', '‚úò'),
            (string) $e,
            $time,
            true
        );
    }

    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        $this->lastTestFailed    = true;
        $this->testResultMessage = $this->formatTestResultMessage(
            $this->formatWithColor('fg-red', '‚úò'),
            (string) $e,
            $time,
            true
        );
    }

    public function addIncompleteTest(Test $test, \Throwable $t, float $time): void
    {
        $this->lastTestFailed    = true;
        $this->testResultMessage = $this->formatTestResultMessage(
            $this->formatWithColor('fg-yellow', '‚àÖ'),
            (string) $t,
            $time,
            false
        );
    }

    public function addRiskyTest(Test $test, \Throwable $t, float $time): void
    {
        $this->lastTestFailed    = true;
        $this->testResultMessage = $this->formatTestResultMessage(
            $this->formatWithColor('fg-yellow', '‚ò¢'),
            (string) $t,
            $time,
            false
        );
    }

    public function addSkippedTest(Test $test, \Throwable $t, float $time): void
    {
        $this->lastTestFailed    = true;
        $this->testResultMessage = $this->formatTestResultMessage(
            $this->formatWithColor('fg-yellow', '‚Üí'),
            (string) $t,
            $time,
            false
        );
    }

    public function bufferTestResult(Test $test, string $msg): void
    {
        $this->outputBuffer[$this->testIndex] = [
            'className'  => $this->className,
            'testName'   => TestSuiteSorter::getTestSorterUID($test),
            'testMethod' => $this->testMethod,
            'message'    => $msg,
            'failed'     => $this->lastTestFailed,
            'verbose'    => $this->lastFlushedTestWasVerbose,
        ];
    }

    public function writeTestResult(string $msg): void
    {
        $msg = $this->formatTestSuiteHeader($this->lastClassName, $this->className, $msg);
        $this->write($msg);
    }

    public function writeProgress(string $progress): void
    {
    }

    public function flush(): void
    {
    }

    public function printResult(TestResult $result): void
    {
        $this->printHeader();

        $this->printNonSuccessfulTestsSummary($result->count());

        $this->printFooter($result);
    }

    protected function printHeader(): void
    {
        $this->write("\n" . Timer::resourceUsage() . "\n\n");
    }

    private function flushOutputBuffer(): void
    {
        if ($this->testFlushIndex === $this->testIndex) {
            return;
        }

        if ($this->testFlushIndex > 0) {
            $prevResult = $this->getTestResultByName($this->originalExecutionOrder[$this->testFlushIndex - 1]);
        } else {
            $prevResult = $this->getEmptyTestResult();
        }

        do {
            $flushed = false;
            $result  = $this->getTestResultByName($this->originalExecutionOrder[$this->testFlushIndex]);

            if (!empty($result)) {
                $this->writeBufferTestResult($prevResult, $result);
                $this->testFlushIndex++;
                $prevResult = $result;
                $flushed    = true;
            }
        } while ($flushed && $this->testFlushIndex < $this->testIndex);
    }

    private function writeBufferTestResult(array $prevResult, array $result): void
    {
        // Write spacer line for new suite headers and after verbose messages
        if ($prevResult['testName'] !== '' &&
            ($prevResult['verbose'] === true || $prevResult['className'] !== $result['className'])) {
            $this->write("\n");
        }

        // Write suite header
        if ($prevResult['className'] !== $result['className']) {
            $this->write($result['className'] . "\n");
        }

        // Write the test result itself
        $this->write($result['message']);
    }

    private function getTestResultByName(string $testName): array
    {
        foreach ($this->outputBuffer as $result) {
            if ($result['testName'] === $testName) {
                return $result;
            }
        }

        return [];
    }

    private function formatTestSuiteHeader(?string $lastClassName, string $className, string $msg): string
    {
        if ($lastClassName === null || $className !== $lastClassName) {
            return \sprintf(
                "%s%s\n%s",
                ($this->lastClassName !== '') ? "\n" : '',
                $className,
                $msg
            );
        }

        return $msg;
    }

    private function formatTestResultMessage(
        string $symbol,
        string $resultMessage,
        float $time,
        bool $alwaysVerbose = false
    ): string {
        $additionalInformation = $this->getFormattedAdditionalInformation($resultMessage, $alwaysVerbose);
        $msg                   = \sprintf(
            " %s %s%s\n%s",
            $symbol,
            $this->testMethod,
            $this->verbose ? ' ' . $this->getFormattedRuntime($time) : '',
            $additionalInformation
        );

        $this->lastFlushedTestWasVerbose = !empty($additionalInformation);

        return $msg;
    }

    private function getFormattedRuntime(float $time): string
    {
        if ($time > 5) {
            return $this->formatWithColor('fg-red', \sprintf('[%.2f ms]', $time * 1000));
        }

        if ($time > 1) {
            return $this->formatWithColor('fg-yellow', \sprintf('[%.2f ms]', $time * 1000));
        }

        return \sprintf('[%.2f ms]', $time * 1000);
    }

    private function getFormattedAdditionalInformation(string $resultMessage, bool $verbose): string
    {
        if ($resultMessage === '') {
            return '';
        }

        if (!($this->verbose || $verbose)) {
            return '';
        }

        return \sprintf(
            "   ‚îÇ\n%s\n",
            \implode(
                "\n",
                \array_map(
                    function (string $text) {
                        return \sprintf('   ‚îÇ %s', $text);
                    },
                    \explode("\n", $resultMessage)
                )
            )
        );
    }

    private function printNonSuccessfulTestsSummary(int $numberOfExecutedTests): void
    {
        if (empty($this->nonSuccessfulTestResults)) {
            return;
        }

        if ((\count($this->nonSuccessfulTestResults) / $numberOfExecutedTests) >= 0.7) {
            return;
        }

        $this->write("Summary of non-successful tests:\n\n");

        $prevResult = $this->getEmptyTestResult();

        foreach ($this->nonSuccessfulTestResults as $testIndex) {
            $result = $this->outputBuffer[$testIndex];
            $this->writeBufferTestResult($prevResult, $result);
            $prevResult = $result;
        }
    }

    private function getEmptyTestResult(): array
    {
        return [
            'className' => '',
            'testName'  => '',
            'message'   => '',
            'failed'    => '',
            'verbose'   => '',
        ];
    }
}
                                                                                                                                    <?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util\TestDox;

/**
 * Prints TestDox documentation in HTML format.
 */
final class HtmlResultPrinter extends ResultPrinter
{
    /**
     * @var string
     */
    private const PAGE_HEADER = <<<EOT
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>Test Documentation</title>
        <style>
            body {
                text-rendering: optimizeLegibility;
                font-variant-ligatures: common-ligatures;
                font-kerning: normal;
                margin-left: 2em;
            }

            body > ul > li {
                font-family: Source Serif Pro, PT Sans, Trebuchet MS, Helvetica, Arial;
                font-size: 2em;
            }

            h2 {
                font-family: Tahoma, Helvetica, Arial;
                font-size: 3em;
            }

            ul {
                list-style: none;
                margin-bottom: 1em;
            }
        </style>
    </head>
    <body>
EOT;

    /**
     * @var string
     */
    private const CLASS_HEADER = <<<EOT

        <h2 id="%s">%s</h2>
        <ul>

EOT;

    /**
     * @var string
     */
    private const CLASS_FOOTER = <<<EOT
        </ul>
EOT;

    /**
     * @var string
     */
    private const PAGE_FOOTER = <<<EOT

    </body>
</html>
EOT;

    /**
     * Handler for 'start run' event.
     */
    protected function startRun(): void
    {
        $this->write(self::PAGE_HEADER);
    }

    /**
     * Handler for 'start class' event.
     */
    protected function startClass(string $name): void
    {
        $this->write(
            \sprintf(
                self::CLASS_HEADER,
                $name,
                $this->currentTestClassPrettified
            )
        );
    }

    /**
     * Handler for 'on test' event.
     */
    protected function onTest($name, bool $success = true): void
    {
        $this->write(
            \sprintf(
                "            <li style=\"color: %s;\">%s %s</li>\n",
                $success ? '#555753' : '#ef2929',
                $success ? '‚úì' : '‚ùå',
                $name
            )
        );
    }

    /**
     * Handler for 'end class' event.
     */
    protected function endClass(string $name): void
    {
        $this->write(self::CLASS_FOOTER);
    }

    /**
     * Handler for 'end run' event.
     */
    protected function endRun(): void
    {
        $this->write(self::PAGE_FOOTER);
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util\TestDox;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\Exporter\Exporter;

/**
 * Prettifies class and method names for use in TestDox documentation.
 */
final class NamePrettifier
{
    /**
     * @var array
     */
    private $strings = [];

    /**
     * Prettifies the name of a test class.
     */
    public function prettifyTestClass(string $className): string
    {
        try {
            $annotations = \PHPUnit\Util\Test::parseTestMethodAnnotations($className);

            if (isset($annotations['class']['testdox'][0])) {
                return $annotations['class']['testdox'][0];
            }
        } catch (\ReflectionException $e) {
        }

        $result = $className;

        if (\substr($className, -1 * \strlen('Test')) === 'Test') {
            $result = \substr($result, 0, \strripos($result, 'Test'));
        }

        if (\strpos($className, 'Tests') === 0) {
            $result = \substr($result, \strlen('Tests'));
        } elseif (\strpos($className, 'Test') === 0) {
            $result = \substr($result, \strlen('Test'));
        }

        if ($result[0] === '\\') {
            $result = \substr($result, 1);
        }

        return $result;
    }

    /**
     * @throws \ReflectionException
     */
    public function prettifyTestCase(TestCase $test): string
    {
        $annotations                = $test->getAnnotations();
        $annotationWithPlaceholders = false;

        $callback = static function (string $variable): string {
            return \sprintf('/%s(?=\b)/', \preg_quote($variable, '/'));
        };

        if (isset($annotations['method']['testdox'][0])) {
            $result = $annotations['method']['testdox'][0];

            if (\strpos($result, '$') !== false) {
                $annotation   = $annotations['method']['testdox'][0];
                $providedData = $this->mapTestMethodParameterNamesToProvidedDataValues($test);
                $variables    = \array_map($callback, \array_keys($providedData));

                $result = \trim(\preg_replace($variables, $providedData, $annotation));

                $annotationWithPlaceholders = true;
            }
        } else {
            $result = $this->prettifyTestMethod($test->getName(false));
        }

        if ($test->usesDataProvider() && !$annotationWithPlaceholders) {
            $result .= $test->getDataSetAsString(false);
        }

        return $result;
    }

    /**
     * Prettifies the name of a test method.
     */
    public function prettifyTestMethod(string $name): string
    {
        $buffer = '';

        if (!\is_string($name) || $name === '') {
            return $buffer;
        }

        $string = \preg_replace('#\d+$#', '', $name, -1, $count);

        if (\in_array($string, $this->strings)) {
            $name = $string;
        } elseif ($count === 0) {
            $this->strings[] = $string;
        }

        if (\strpos($name, 'test_') === 0) {
            $name = \substr($name, 5);
        } elseif (\strpos($name, 'test') === 0) {
            $name = \substr($name, 4);
        }

        if ($name === '') {
            return $buffer;
        }

        $name[0] = \strtoupper($name[0]);

        if (\strpos($name, '_') !== false) {
            return \trim(\str_replace('_', ' ', $name));
        }

        $max        = \strlen($name);
        $wasNumeric = false;

        for ($i = 0; $i < $max; $i++) {
            if ($i > 0 && \ord($name[$i]) >= 65 && \ord($name[$i]) <= 90) {
                $buffer .= ' ' . \strtolower($name[$i]);
            } else {
                $isNumeric = \is_numeric($name[$i]);

                if (!$wasNumeric && $isNumeric) {
                    $buffer .= ' ';
                    $wasNumeric = true;
                }

                if ($wasNumeric && !$isNumeric) {
                    $wasNumeric = false;
                }

                $buffer .= $name[$i];
            }
        }

        return $buffer;
    }

    /**
     * @throws \ReflectionException
     */
    private function mapTestMethodParameterNamesToProvidedDataValues(TestCase $test): array
    {
        $reflector          = new \ReflectionMethod(\get_class($test), $test->getName(false));
        $providedData       = [];
        $providedDataValues = \array_values($test->getProvidedData());
        $i                  = 0;

        foreach ($reflector->getParameters() as $parameter) {
            if (!\array_key_exists($i, $providedDataValues) && $parameter->isDefaultValueAvailable()) {
                $providedDataValues[$i] = $parameter->getDefaultValue();
            }

            $value = $providedDataValues[$i++] ?? null;

            if (\is_object($value)) {
                $reflector = new \ReflectionObject($value);

                if ($reflector->hasMethod('__toString')) {
                    $value = (string) $value;
                }
            }

            if (!\is_scalar($value)) {
                $value = \gettype($value);
            }

            if (\is_bool($value) || \is_int($value) || \is_float($value)) {
                $exporter = new Exporter;

                $value = $exporter->export($value);
            }

            $providedData['$' . $parameter->getName()] = $value;
        }

        return $providedData;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           <?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util\TestDox;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;
use PHPUnit\Framework\WarningTestCase;
use PHPUnit\Runner\BaseTestRunner;
use PHPUnit\Util\Printer;

/**
 * Base class for printers of TestDox documentation.
 */
abstract class ResultPrinter extends Printer implements TestListener
{
    /**
     * @var NamePrettifier
     */
    protected $prettifier;

    /**
     * @var string
     */
    protected $testClass = '';

    /**
     * @var int
     */
    protected $testStatus;

    /**
     * @var array
     */
    protected $tests = [];

    /**
     * @var int
     */
    protected $successful = 0;

    /**
     * @var int
     */
    protected $warned = 0;

    /**
     * @var int
     */
    protected $failed = 0;

    /**
     * @var int
     */
    protected $risky = 0;

    /**
     * @var int
     */
    protected $skipped = 0;

    /**
     * @var int
     */
    protected $incomplete = 0;

    /**
     * @var null|string
     */
    protected $currentTestClassPrettified;

    /**
     * @var null|string
     */
    protected $currentTestMethodPrettified;

    /**
     * @var array
     */
    private $groups;

    /**
     * @var array
     */
    private $excludeGroups;

    /**
     * @param resource $out
     *
     * @throws \PHPUnit\Framework\Exception
     */
    public function __construct($out = null, array $groups = [], array $excludeGroups = [])
    {
        parent::__construct($out);

        $this->groups        = $groups;
        $this->excludeGroups = $excludeGroups;

        $this->prettifier = new NamePrettifier;
        $this->startRun();
    }

    /**
     * Flush buffer and close output.
     */
    public function flush(): void
    {
        $this->doEndClass();
        $this->endRun();

        parent::flush();
    }

    /**
     * An error occurred.
     */
    public function addError(Test $test, \Throwable $t, float $time): void
    {
        if (!$this->isOfInterest($test)) {
            return;
        }

        $this->testStatus = BaseTestRunner::STATUS_ERROR;
        $this->failed++;
    }

    /**
     * A warning occurred.
     */
    public function addWarning(Test $test, Warning $e, float $time): void
    {
        if (!$this->isOfInterest($test)) {
            return;
        }

        $this->testStatus = BaseTestRunner::STATUS_WARNING;
        $this->warned++;
    }

    /**
     * A failure occurred.
     */
    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        if (!$this->isOfInterest($test)) {
            return;
        }

        $this->testStatus = BaseTestRunner::STATUS_FAILURE;
        $this->failed++;
    }

    /**
     * Incomplete test.
     */
    public function addIncompleteTest(Test $test, \Throwable $t, float $time): void
    {
        if (!$this->isOfInterest($test)) {
            return;
        }

        $this->testStatus = BaseTestRunner::STATUS_INCOMPLETE;
        $this->incomplete++;
    }

    /**
     * Risky test.
     */
    public function addRiskyTest(Test $test, \Throwable $t, float $time): void
    {
        if (!$this->isOfInterest($test)) {
            return;
        }

        $this->testStatus = BaseTestRunner::STATUS_RISKY;
        $this->risky++;
    }

    /**
     * Skipped test.
     */
    public function addSkippedTest(Test $test, \Throwable $t, float $time): void
    {
        if (!$this->isOfInterest($test)) {
            return;
        }

        $this->testStatus = BaseTestRunner::STATUS_SKIPPED;
        $this->skipped++;
    }

    /**
     * A testsuite started.
     */
    public function startTestSuite(TestSuite $suite): void
    {
    }

    /**
     * A testsuite ended.
     */
    public function endTestSuite(TestSuite $suite): void
    {
    }

    /**
     * A test started.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function startTest(Test $test): void
    {
        if (!$this->isOfInterest($test)) {
            return;
        }

        $class = \get_class($test);

        if ($this->testClass !== $class) {
            if ($this->testClass !== '') {
                $this->doEndClass();
            }

            $this->currentTestClassPrettified = $this->prettifier->prettifyTestClass($class);
            $this->testClass                  = $class;
            $this->tests                      = [];

            $this->startClass($class);
        }

        if ($test instanceof TestCase) {
            $this->currentTestMethodPrettified = $this->prettifier->prettifyTestCase($test);
        }

        $this->testStatus = BaseTestRunner::STATUS_PASSED;
    }

    /**
     * A test ended.
     */
    public function endTest(Test $test, float $time): void
    {
        if (!$this->isOfInterest($test)) {
            return;
        }

        $this->tests[] = [$this->currentTestMethodPrettified, $this->testStatus];

        $this->currentTestClassPrettified  = null;
        $this->currentTestMethodPrettified = null;
    }

    protected function doEndClass(): void
    {
        foreach ($this->tests as $test) {
            $this->onTest($test[0], $test[1] === BaseTestRunner::STATUS_PASSED);
        }

        $this->endClass($this->testClass);
    }

    /**
     * Handler for 'start run' event.
     */
    protected function startRun(): void
    {
    }

    /**
     * Handler for 'start class' event.
     */
    protected function startClass(string $name): void
    {
    }

    /**
     * Handler for 'on test' event.
     */
    protected function onTest($name, bool $success = true): void
    {
    }

    /**
     * Handler for 'end class' event.
     */
    protected function endClass(string $name): void
    {
    }

    /**
     * Handler for 'end run' event.
     */
    protected function endRun(): void
    {
    }

    private function isOfInterest(Test $test): bool
    {
        if (!$test instanceof TestCase) {
            return false;
        }

        if ($test instanceof WarningTestCase) {
            return false;
        }

        if (!empty($this->groups)) {
            foreach ($test->getGroups() as $group) {
                if (\in_array($group, $this->groups)) {
                    return true;
                }
            }

            return false;
        }

        if (!empty($this->excludeGroups)) {
            foreach ($test->getGroups() as $group) {
                if (\in_array($group, $this->excludeGroups)) {
                    return false;
                }
            }

            return true;
        }

        return true;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             <?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util\TestDox;

final class TestResult
{
    /**
     * @var callable
     */
    private $colorize;

    /**
     * @var string
     */
    private $testClass;

    /**
     * @var string
     */
    private $testMethod;

    /**
     * @var bool
     */
    private $testSuccesful;

    /**
     * @var string
     */
    private $symbol;

    /**
     * @var string
     */
    private $additionalInformation;

    /**
     * @var bool
     */
    private $additionalInformationVerbose;

    /**
     * @var float
     */
    private $runtime;

    public function __construct(callable $colorize, string $testClass, string $testMethod)
    {
        $this->colorize              = $colorize;
        $this->testClass             = $testClass;
        $this->testMethod            = $testMethod;
        $this->testSuccesful         = true;
        $this->symbol                = ($this->colorize)('fg-green', '‚úî');
        $this->additionalInformation = '';
    }

    public function isTestSuccessful(): bool
    {
        return $this->testSuccesful;
    }

    public function fail(string $symbol, string $additionalInformation, bool $additionalInformationVerbose = false): void
    {
        $this->testSuccesful                = false;
        $this->symbol                       = $symbol;
        $this->additionalInformation        = $additionalInformation;
        $this->additionalInformationVerbose = $additionalInformationVerbose;
    }

    public function setRuntime(float $runtime): void
    {
        $this->runtime = $runtime;
    }

    public function toString(?self $previousTestResult, $verbose = false): string
    {
        return \sprintf(
            "%s%s %s %s%s\n%s",
            $previousTestResult && $previousTestResult->additionalInformationPrintable($verbose) ? "\n" : '',
            $this->getClassNameHeader($previousTestResult ? $previousTestResult->testClass : null),
            $this->symbol,
            $this->testMethod,
            $verbose ? ' ' . $this->getFormattedRuntime() : '',
            $this->getFormattedAdditionalInformation($verbose)
        );
    }

    private function getClassNameHeader(?string $previousTestClass): string
    {
        $className = '';

        if ($this->testClass !== $previousTestClass) {
            if (null !== $previousTestClass) {
                $className = "\n";
            }

            $className .= \sprintf("%s\n", $this->testClass);
        }

        return $className;
    }

    private function getFormattedRuntime(): string
    {
        if ($this->runtime > 5) {
            return ($this->colorize)('fg-red', \sprintf('[%.2f ms]', $this->runtime * 1000));
        }

        if ($this->runtime > 1) {
            return ($this->colorize)('fg-yellow', \sprintf('[%.2f ms]', $this->runtime * 1000));
        }

        return \sprintf('[%.2f ms]', $this->runtime * 1000);
    }

    private function getFormattedAdditionalInformation($verbose): string
    {
        if (!$this->additionalInformationPrintable($verbose)) {
            return '';
        }

        return \sprintf(
            "   ‚îÇ\n%s\n",
            \implode(
                "\n",
                \array_map(
                    function (string $text) {
                        return \sprintf('   ‚îÇ %s', $text);
                    },
                    \explode("\n", $this->additionalInformation)
                )
            )
        );
    }

    private function additionalInformationPrintable(bool $verbose): bool
    {
        if ($this->additionalInformation === '') {
            return false;
        }

        if ($this->additionalInformationVerbose && !$verbose) {
            return false;
        }

        return true;
    }
}
                                                                                                                        INDX( 	 ¶Cé             (   ò  Ë                             $"     Ä l     #"     ‹d@}pk’ Oµ
˜‘ÅdÎ´‹<’‹d@}pk’ 0      |/               C l i T e s t D o x P r i n t e r . p h p     %"     Ä l     #"     wåG}pk’ Oµ
˜‘ÅdÎ´‹<’wåG}pk’       z
               H t m l R e s u l t P r i n t e r . p h p     &"     x f     #"     ∆ÓI}pk’ Oµ
˜‘–≈Ì´‹<’∆ÓI}pk’        µ               N a m e P r e t t i f i e r . p h p   '"     x d     #"     í≥N}pk’ Oµ
˜‘W(´‹<’í≥N}pk’        c              R e s u l t P r i n t e r . p h p     ("     p ^     #"     °wS}pk’ Oµ
˜‘ßãÚ´‹<’°wS}pk’       à               T e s t R e s u l t . p h p   )"     Ä l     #"     î⁄U}pk’ Oµ
˜‘ÊÏÙ´‹<’î⁄U}pk’       4               T e x t R e s u l t P r i n t e r . p h p     *"     Ä j     #"     ∑ûZ}pk’ Oµ
˜‘ÊÏÙ´‹<’∑ûZ}pk’        ¯               X m l R e s u l t P r i n t e r . p h p                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util\TestDox;

/**
 * Prints TestDox documentation in text format to files.
 * For the CLI testdox printer please refer to \PHPUnit\TextUI\TextDoxPrinter.
 */
class TextResultPrinter extends ResultPrinter
{
    /**
     * Handler for 'start class' event.
     */
    protected function startClass(string $name): void
    {
        $this->write($this->currentTestClassPrettified . "\n");
    }

    /**
     * Handler for 'on test' event.
     */
    protected function onTest($name, bool $success = true): void
    {
        if ($success) {
            $this->write(' [x] ');
        } else {
            $this->write(' [ ] ');
        }

        $this->write($name . "\n");
    }

    /**
     * Handler for 'end class' event.
     */
    protected function endClass(string $name): void
    {
        $this->write("\n");
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright