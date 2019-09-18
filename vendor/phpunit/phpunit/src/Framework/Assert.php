e
                );

                $_message = $e->getMessage();

                if (!empty($_message)) {
                    $message .= "\n" . $_message;
                }

                $data = self::skipTest($className, $name, $message);
            } catch (Throwable $t) {
                $message = \sprintf(
                    'The data provider specified for %s::%s is invalid.',
                    $className,
                    $name
                );

                $_message = $t->getMessage();

                if (!empty($_message)) {
                    $message .= "\n" . $_message;
                }

                $data = self::warning($message);
            }

            // Test method with @dataProvider.
            if (isset($data)) {
                $test = new DataProviderTestSuite(
                    $className . '::' . $name
                );

                if (empty($data)) {
                    $data = self::warning(
                        \sprintf(
                            'No tests found in suite "%s".',
                            $test->getName()
                        )
                    );
                }

                $groups = \PHPUnit\Util\Test::getGroups($className, $name);

                if ($data instanceof WarningTestCase ||
                    $data instanceof SkippedTestCase ||
                    $data instanceof IncompleteTestCase) {
                    $test->addTest($data, $groups);
                } else {
                    foreach ($data as $_dataName => $_data) {
                        $_test = new $className($name, $_data, $_dataName);

                        /* @var TestCase $_test */

                        if ($runTestInSeparateProcess) {
                            $_test->setRunTestInSeparateProcess(true);

                            if ($preserveGlobalState !== null) {
                                $_test->setPreserveGlobalState($preserveGlobalState);
                            }
                        }

                        if ($runClassInSeparateProcess) {
                            $_test->setRunClassInSeparateProcess(true);

                            if ($preserveGlobalState !== null) {
                                $_test->setPreserveGlobalState($preserveGlobalState);
                            }
                        }

                        if ($backupSettings['backupGlobals'] !== null) {
                            $_test->setBackupGlobals(
                                $backupSettings['backupGlobals']
                            );
                        }

                        if ($backupSettings['backupStaticAttributes'] !== null) {
                            $_test->setBackupStaticAttributes(
                                $backupSettings['backupStaticAttributes']
                            );
                        }

                        $test->addTest($_test, $groups);
                    }
                }
            } else {
                $test = new $className;
            }
        }

        if ($test instanceof TestCase) {
            $test->setName($name);

            if ($runTestInSeparateProcess) {
                $test->setRunTestInSeparateProcess(true);

                if ($preserveGlobalState !== null) {
                    $test->setPreserveGlobalState($preserveGlobalState);
                }
            }

            if ($runClassInSeparateProcess) {
                $test->setRunClassInSeparateProcess(true);

                if ($preserveGlobalState !== null) {
                    $test->setPreserveGlobalState($preserveGlobalState);
                }
            }

            if ($backupSettings['backupGlobals'] !== null) {
                $test->setBackupGlobals($backupSettings['backupGlobals']);
            }

            if ($backupSettings['backupStaticAttributes'] !== null) {
                $test->setBackupStaticAttributes(
                    $backupSettings['backupStaticAttributes']
                );
            }
        }

        return $test;
    }

    public static function isTestMethod(ReflectionMethod $method): bool
    {
        if (\strpos($method->name, 'test') === 0) {
            return true;
        }

        $annotations = \PHPUnit\Util\Test::parseAnnotations($method->getDocComment());

        return isset($annotations['test']);
    }

    /**
     * Constructs a new TestSuite:
     *
     *   - PHPUnit\Framework\TestSuite() constructs an empty TestSuite.
     *
     *   - PHPUnit\Framework\TestSuite(ReflectionClass) constructs a
     *     TestSuite from the given class.
     *
     *   - PHPUnit\Framework\TestSuite(ReflectionClass, String)
     *     constructs a TestSuite from the given class with the given
     *     name.
     *
     *   - PHPUnit\Framework\TestSuite(String) either constructs a
     *     TestSuite from the given class (if the passed string is the
     *     name of an existing class) or constructs an empty TestSuite
     *     with the given name.
     *
     * @param string $name
     *
     * @throws Exception
     */
    public function __construct($theClass = '', $name = '')
    {
        $this->declaredClasses = \get_declared_classes();

        $argumentsValid = false;

        if (\is_object($theClass) &&
            $theClass instanceof ReflectionClass) {
            $argumentsValid = true;
        } elseif (\is_string($theClass) &&
            $theClass !== '' &&
            \class_exists($theClass, true)) {
            $argumentsValid = true;

            if ($name == '') {
                $name = $theClass;
            }

            $theClass = new ReflectionClass($theClass);
        } elseif (\is_string($theClass)) {
            $this->setName($theClass);

            return;
        }

        if (!$argumentsValid) {
            throw new Exception;
        }

        if (!$theClass->isSubclassOf(TestCase::class)) {
            $this->setName($theClass);

            return;
        }

        if ($name != '') {
            $this->setName($name);
        } else {
            $this->setName($theClass->getName());
        }

        $constructor = $theClass->getConstructor();

        if ($constructor !== null &&
            !$constructor->isPublic()) {
            $this->addTest(
                self::warning(
                    \sprintf(
                        'Class "%s" has no public constructor.',
                        $theClass->getName()
                    )
                )
            );

            return;
        }

        foreach ($theClass->getMethods() as $method) {
            if ($method->getDeclaringClass()->getName() === Assert::class) {
                continue;
            }

            if ($method->getDeclaringClass()->getName() === TestCase::class) {
                continue;
            }

            $this->addTestMethod($theClass, $method);
        }

        if (empty($this->tests)) {
            $this->addTest(
                self::warning(
                    \sprintf(
                        'No tests found in class "%s".',
                        $theClass->getName()
                    )
                )
            );
        }

        $this->testCase = true;
    }

    /**
     * Template Method that is called before the tests
     * of this test suite are run.
     */
    protected function setUp(): void
    {
    }

    /**
     * Template Method that is called after the tests
     * of this test suite have finished running.
     */
    protected function tearDown(): void
    {
    }

    /**
     * Returns a string representation of the test suite.
     */
    public function toString(): string
    {
        return $this->getName();
    }

    /**
     * Adds a test to the suite.
     *
     * @param array $groups
     */
    public function addTest(Test $test, $groups = []): void
    {
        $class = new ReflectionClass($test);

        if (!$class->isAbstract()) {
            $this->tests[]  = $test;
            $this->numTests = -1;

            if ($test instanceof self && empty($groups)) {
                $groups = $test->getGroups();
            }

            if (empty($groups)) {
                $groups = ['default'];
            }

            foreach ($groups as $group) {
                if (!isset($this->groups[$group])) {
                    $this->groups[$group] = [$test];
                } else {
                    $this->groups[$group][] = $test;
                }
            }

            if ($test instanceof TestCase) {
                $test->setGroups($groups);
            }
        }
    }

    /**
     * Adds the tests from the given class to the suite.
     *
     * @throws Exception
     */
    public function addTestSuite($testClass): void
    {
        if (\is_string($testClass) && \class_exists($testClass)) {
            $testClass = new ReflectionClass($testClass);
        }

        if (!\is_object($testClass)) {
            throw InvalidArgumentHelper::factory(
                1,
                'class name or object'
            );
        }

        if ($testClass instanceof self) {
            $this->addTest($testClass);
        } elseif ($testClass instanceof ReflectionClass) {
            $suiteMethod = false;

            if (!$testClass->isAbstract() && $testClass->hasMethod(BaseTestRunner::SUITE_METHODNAME)) {
                $method = $testClass->getMethod(
                    BaseTestRunner::SUITE_METHODNAME
                );

                if ($method->isStatic()) {
                    $this->addTest(
                        $method->invoke(null, $testClass->getName())
                    );

                    $suiteMethod = true;
                }
            }

            if (!$suiteMethod && !$testClass->isAbstract() && $testClass->isSubclassOf(TestCase::class)) {
                $this->addTest(new self($testClass));
            }
        } else {
            throw new Exception;
        }
    }

    /**
     * Wraps both <code>addTest()</code> and <code>addTestSuite</code>
     * as well as the separate import statements for the user's convenience.
     *
     * If the named file cannot be read or there are no new tests that can be
     * added, a <code>PHPUnit\Framework\WarningTestCase</code> will be created instead,
     * leaving the current test run untouched.
     *
     * @throws Exception
     */
    public function addTestFile(string $filename): void
    {
        if (\file_exists($filename) && \substr($filename, -5) == '.phpt') {
            $this->addTest(
                new PhptTestCase($filename)
            );

            return;
        }

        // The given file may contain further stub classes in addition to the
        // test class itself. Figure out the actual test class.
        $filename   = FileLoader::checkAndLoad($filename);
        $newClasses = \array_diff(\get_declared_classes(), $this->declaredClasses);

        // The diff is empty in case a parent class (with test methods) is added
        // AFTER a child class that inherited from it. To account for that case,
        // accumulate all discovered classes, so the parent class may be found in
        // a later invocation.
        if (!empty($newClasses)) {
            // On the assumption that test classes are defined first in files,
            // process discovered classes in approximate LIFO order, so as to
            // avoid unnecessary reflection.
            $this->foundClasses    = \array_merge($newClasses, $this->foundClasses);
            $this->declaredClasses = \get_declared_classes();
        }

        // The test class's name must match the filename, either in full, or as
        // a PEAR/PSR-0 prefixed short name ('NameSpace_ShortName'), or as a
        // PSR-1 local short name ('NameSpace\ShortName'). The comparison must be
        // anchored to prevent false-positive matches (e.g., 'OtherShortName').
        $shortName      = \basename($filename, '.php');
        $shortNameRegEx = '/(?:^|_|\\\\)' . \preg_quote($shortName, '/') . '$/';

        foreach ($this->foundClasses as $i => $className) {
            if (\preg_match($shortNameRegEx, $className)) {
                $class = new ReflectionClass($className);

                if ($class->getFileName() == $filename) {
                    $newClasses = [$className];
                    unset($this->foundClasses[$i]);

                    break;
                }
            }
        }

        foreach ($newClasses as $className) {
            $class = new ReflectionClass($className);

            if (\dirname($class->getFileName()) === __DIR__) {
                continue;
            }

            if (!$class->isAbstract()) {
                if ($class->hasMethod(BaseTestRunner::SUITE_METHODNAME)) {
                    $method = $class->getMethod(
                        BaseTestRunner::SUITE_METHODNAME
                    );

                    if ($method->isStatic()) {
                        $this->addTest($method->invoke(null, $className));
                    }
                } elseif ($class->implementsInterface(Test::class)) {
                    $this->addTestSuite($class);
                }
            }
        }

        $this->numTests = -1;
    }

    /**
     * Wrapper for addTestFile() that adds multiple test files.
     *
     * @param array|Iterator $fileNames
     *
     * @throws Exception
     */
    public function addTestFiles($fileNames): void
    {
        if (!(\is_array($fileNames) ||
            (\is_object($fileNames) && $fileNames instanceof Iterator))) {
            throw InvalidArgumentHelper::factory(
                1,
                'array or iterator'
            );
        }

        foreach ($fileNames as $filename) {
            $this->addTestFile((string) $filename);
        }
    }

    /**
     * Counts the number of test cases that will be run by this test.
     *
     * @param bool $preferCache indicates if cache is preferred
     */
    public function count($preferCache = false): int
    {
        if ($preferCache && $this->cachedNumTests !== null) {
            return $this->cachedNumTests;
        }

        $numTests = 0;

        foreach ($this as $test) {
            $numTests += \count($test);
        }

        $this->cachedNumTests = $numTests;

        return $numTests;
    }

    /**
     * Returns the name of the suite.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the test groups of the suite.
     */
    public function getGroups(): array
    {
        return \array_keys($this->groups);
    }

    public function getGroupDetails()
    {
        return $this->groups;
    }

    /**
     * Set tests groups of the test case
     */
    public function setGroupDetails(array $groups): void
    {
        $this->groups = $groups;
    }

    /**
     * Runs the tests and collects their result in a TestResult.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function run(TestResult $result = null): TestResult
    {
        if ($result === null) {
            $result = $this->createResult();
        }

        if (\count($this) == 0) {
            return $result;
        }

        $hookMethods = \PHPUnit\Util\Test::getHookMethods($this->name);

        $result->startTestSuite($this);

        try {
            $this->setUp();

            foreach ($hookMethods['beforeClass'] as $beforeClassMethod) {
                if ($this->testCase === true &&
                    \class_exists($this->name, false) &&
                    \method_exists($this->name, $beforeClassMethod)) {
                    if ($missingRequirements = \PHPUnit\Util\Test::getMissingRequirements($this->name, $beforeClassMethod)) {
                        $this->markTestSuiteSkipped(\implode(\PHP_EOL, $missingRequirements));
                    }

                    \call_user_func([$this->name, $beforeClassMethod]);
                }
            }
        } catch (SkippedTestSuiteError $error) {
            foreach ($this->tests() as $test) {
                $result->startTest($test);
                $result->addFailure($test, $error, 0);
                $result->endTest($test, 0);
            }

            $this->tearDown();
            $result->endTestSuite($this);

            return $result;
        } catch (Throwable $t) {
            foreach ($this->tests() as $test) {
                if ($result->shouldStop()) {
                    break;
                }

                $result->startTest($test);
                $result->addError($test, $t, 0);
                $result->endTest($test, 0);
            }

            $this->tearDown();
            $result->endTestSuite($this);

            return $result;
        }

        foreach ($this as $test) {
            if ($result->shouldStop()) {
                break;
            }

            if ($test instanceof TestCase || $test instanceof self) {
                $test->setBeStrictAboutChangesToGlobalState($this->beStrictAboutChangesToGlobalState);
                $test->setBackupGlobals($this->backupGlobals);
                $test->setBackupStaticAttributes($this->backupStaticAttributes);
                $test->setRunTestInSeparateProcess($this->runTestInSeparateProcess);
            }

            $test->run($result);
        }

        try {
            foreach ($hookMethods['afterClass'] as $afterClassMethod) {
                if ($this->testCase === true && \class_exists($this->name, false) && \method_exists(
                    $this->name,
                    $afterClassMethod
                )) {
                    \call_user_func([$this->name, $afterClassMethod]);
                }
            }
        } catch (Throwable $t) {
            $message = "Exception in {$this->name}::$afterClassMethod" . \PHP_EOL . $t->getMessage();
            $error   = new SyntheticError($message, 0, $t->getFile(), $t->getLine(), $t->getTrace());

            $placeholderTest = clone $test;
            $placeholderTest->setName($afterClassMethod);

            $result->startTest($placeholderTest);
            $result->addFailure($placeholderTest, $error, 0);
            $result->endTest($placeholderTest, 0);
        }

        $this->tearDown();

        $result->endTestSuite($this);

        return $result;
    }

    public function setRunTestInSeparateProcess(bool $runTestInSeparateProcess): void
    {
        $this->runTestInSeparateProcess = $runTestInSeparateProcess;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns the test at the given index.
     *
     * @return false|Test
     */
    public function testAt(int $index)
    {
        if (isset($this->tests[$index])) {
            return $this->tests[$index];
        }

        return false;
    }

    /**
     * Returns the tests as an enumeration.
     */
    public function tests(): array
    {
        return $this->tests;
    }

    /**
     * Set tests of the test suite
     */
    public function setTests(array $tests): void
    {
        $this->tests = $tests;
    }

    /**
     * Mark the test suite as skipped.
     *
     * @param string $message
     *
     * @throws SkippedTestSuiteError
     */
    public function markTestSuiteSkipped($message = ''): void
    {
        throw new SkippedTestSuiteError($message);
    }

    /**
     * @param bool $beStrictAboutChangesToGlobalState
     */
    public function setBeStrictAboutChangesToGlobalState($beStrictAboutChangesToGlobalState): void
    {
        if (null === $this->beStrictAboutChangesToGlobalState && \is_bool($beStrictAboutChangesToGlobalState)) {
            $this->beStrictAboutChangesToGlobalState = $beStrictAboutChangesToGlobalState;
        }
    }

    /**
     * @param bool $backupGlobals
     */
    public function setBackupGlobals($backupGlobals): void
    {
        if (null === $this->backupGlobals && \is_bool($backupGlobals)) {
            $this->backupGlobals = $backupGlobals;
        }
    }

    /**
     * @param bool $backupStaticAttributes
     */
    public function setBackupStaticAttributes($backupStaticAttributes): void
    {
        if (null === $this->backupStaticAttributes && \is_bool($backupStaticAttributes)) {
            $this->backupStaticAttributes = $backupStaticAttributes;
        }
    }

    /**
     * Returns an iterator for this test suite.
     */
    public function getIterator(): Iterator
    {
        $iterator = new TestSuiteIterator($this);

        if ($this->iteratorFilter !== null) {
            $iterator = $this->iteratorFilter->factory($iterator, $this);
        }

        return $iterator;
    }

    public function injectFilter(Factory $filter): void
    {
        $this->iteratorFilter = $filter;

        foreach ($this as $test) {
            if ($test instanceof self) {
                $test->injectFilter($filter);
            }
        }
    }

    /**
     * Creates a default TestResult object.
     */
    protected function createResult(): TestResult
    {
        return new TestResult;
    }

    /**
     * @throws Exception
     */
    protected function addTestMethod(ReflectionClass $class, ReflectionMethod $method): void
    {
        if (!$this->isTestMethod($method)) {
            return;
        }

        $name = $method->getName();

        if (!$method->isPublic()) {
            $this->addTest(
                self::warning(
                    \sprintf(
                        'Test method "%s" in test class "%s" is not public.',
                        $name,
                        $class->getName()
                    )
                )
            );

            return;
        }

        $test = self::createTest($class, $name);

        if ($test instanceof TestCase || $test instanceof DataProviderTestSuite) {
            $test->setDependencies(
                \PHPUnit\Util\Test::getDependencies($class->getName(), $name)
            );
        }

        $this->addTest(
            $test,
            \PHPUnit\Util\Test::getGroups($class->getName(), $name)
        );
    }

    /**
     * @param string $message
     */
    protected static function warning($message): WarningTestCase
    {
        return new WarningTestCase($message);
    }

    /**
     * @param string $class
     * @param string $methodName
     * @param string $message
     */
    protected static function skipTest($class, $methodName, $message): SkippedTestCase
    {
        return new SkippedTestCase($class, $methodName, $message);
    }

    /**
     * @param string $class
     * @param string $methodName
     * @param string $message
     */
    protected static function incompleteTest($class, $methodName, $message): IncompleteTestCase
    {
        return new IncompleteTestCase($class, $methodName, $message);
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 <?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework;

use RecursiveIterator;

/**
 * Iterator for test suites.
 */
final class TestSuiteIterator implements RecursiveIterator
{
    /**
     * @var int
     */
    private $position;

    /**
     * @var Test[]
     */
    private $tests;

    public function __construct(TestSuite $testSuite)
    {
        $this->tests = $testSuite->tests();
    }

    /**
     * Rewinds the Iterator to the first element.
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Checks if there is a current element after calls to rewind() or next().
     */
    public function valid(): bool
    {
        return $this->position < \count($this->tests);
    }

    /**
     * Returns the key of the current element.
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * Returns the current element.
     */
    public function current(): Test
    {
        return $this->valid() ? $this->tests[$this->position] : null;
    }

    /**
     * Moves forward to next element.
     */
    public function next(): void
    {
        $this->position++;
    }

    /**
     * Returns the sub iterator for the current element.
     */
    public function getChildren(): self
    {
        return new self(
            $this->tests[$this->position]
        );
    }

    /**
     * Checks whether the current element has children.
     */
    public function hasChildren(): bool
    {
        return $this->tests[$this->position] instanceof TestSuite;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               INDX( 	             (   P  è       d    Õ              R!     x f     A!     N„NupkÕ Oµ
÷Ô;>ªÜ<ÕN„NupkÕØ      ×               S e l f D e s c r i b i n g . p h p   S!     p `     A!     `çPupkÕ Oµ
÷ÔF@ªÜ<Õ`çPupkÕ                      S k i p p e d T e s t . p h p T!     x h     A!     ¥ISupkÕ Oµ
÷Ô¥ CªÜ<Õ¥ISupkÕ       ®               S k i p p e d T e s t C a s e . p h p U!     € j     A!     ó«UupkÕ Oµ
÷Ô¥ CªÜ<Õó«UupkÕX      Q               S k i p p e  T e s t E r r o r . p h p       V!     ˆ t     A!     &pZupkÕ Oµ
÷ÔêbEªÜ<Õ&pZupkÕX      V               S k i p p e d T e s t S u i t e E r r o r . p h p     W!     x f     A!     K˜aupkÕ Oµ
÷Ô…ÄGªÜ<ÕK˜aupkÕ       Ý               S y n t h e t i c E r r o r . p h p   X!     h R     A!     +\fupkÕ Oµ
÷ÔÀ'JªÜ<Õ+\fupkÕ                     T e s t . p h p       Y!     p Z     A!     wåoupkÕ Oµ
÷ÔÀ'JªÜ<ÕwåoupkÕ       ü               T e s t C a s e . p h p       Z!     p `     A!     ÅwupkÕ Oµ
÷Ô‰LªÜ<ÕÅwupkÕ       ½               T e s t F a i l u r e . p h p [!     x b     A!     y3~upkÕ Oµ
÷Ô‘ëNªÜ<Õy3~upkÕ       ¬               T e s t L i s t e n e r . p h p       \!       Œ     A!     òŒupkÕ Oµ
÷ÔÌNQªÜ<ÕòŒupkÕ       n              % T e s t L i s t e n e r D e f a u l t I m p l e m e n t a t i o n . p h p     ]!     p ^     A!     5n˜upkÕ Oµ
÷ÔÌNQªÜ<Õ5n˜upkÕ €      ÷s               T e s t R e s u l t . p h p   ^!     p \    A!     ò2upkÕ Oµ
÷Ô±SªÜ<Õò2upkÕ p      Ÿi               T e s t S u i t e . p h p     _!     € l     A!     EY¤upkÕ Oµ
÷ÔtVªÜ<ÕEY¤upkÕ       ñ               T e s t S u i t e I t e r a t o r . p h p     `!     ˜ ˆ     A!     ©upkÕ Oµ
÷ÔñtXªÜ<Õ©upkÕÐ      Ê              # U n i n t e n t i o n a l l y C o v e r e d C o d e E r r o r . p h p a!     h X     A!     	«upkÕ Oµ
÷Ô+×ZªÜ<Õ	«upkÕ                     W a r n i n g . p h p b!     x h     A!     åE°upk  Oµ
÷Ô+×ZªÜ<ÕåE°upkÕ       â               W a r n i n g T e s t C a s e . p h p                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   <?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework;

/**
 * A warning.
 */
class WarningTestCase extends TestCase
{
    /**
     * @var string
     */
    protected $message = '';

    /**
     * @var bool
     */
    protected $backupGlobals = false;

    /**
     * @var bool
     */
    protected $backupStaticAttributes = false;

    /**
     * @var bool
     */
    protected $runTestInSeparateProcess = false;

    /**
     * @var bool
     */
    protected $useErrorHandler = false;

    /**
     * @param string $message
     */
    public function __construct($message = '')
    {
        $this->message = $message;
        parent::__construct('Warning');
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Returns a string representation of the test case.
     */
    public function toString(): string
    {
        return 'Warning';
    }

    /**
     * @throws Exception
     */
    protected function runTest(): void
    {
        throw new Warning($this->message);
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\ArrayHasKey;
use PHPUnit\Framework\Constraint\Attribute;
use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\Constraint\ClassHasAttribute;
use PHPUnit\Framework\Constraint\ClassHasStaticAttribute;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\Count;
use PHPUnit\Framework\Constraint\DirectoryExists;
use PHPUnit\Framework\Constraint\FileExists;
use PHPUnit\Framework\Constraint\GreaterThan;
use PHPUnit\Framework\Constraint\IsAnything;
use PHPUnit\Framework\Constraint\IsEmpty;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsFalse;
use PHPUnit\Framework\Constraint\IsFinite;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\IsInfinite;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\Constraint\IsJson;
use PHPUnit\Framework\Constraint\IsNan;
use PHPUnit\Framework\Constraint\IsNull;
use PHPUnit\Framework\Constraint\IsReadable;
use PHPUnit\Framework\Constraint\IsTrue;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\Constraint\IsWritable;
use PHPUnit\Framework\Constraint\LessThan;
use PHPUnit\Framework\Constraint\LogicalAnd;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\LogicalOr;
use PHPUnit\Framework\Constraint\LogicalXor;
use PHPUnit\Framework\Constraint\ObjectHasAttribute;
use PHPUnit\Framework\Constraint\RegularExpression;
use PHPUnit\Framework\Constraint\StringContains;
use PHPUnit\Framework\Constraint\StringEndsWith;
use PHPUnit\Framework\Constraint\StringMatchesFormatDescription;
use PHPUnit\Framework\Constraint\StringStartsWith;
use PHPUnit\Framework\Constraint\TraversableContains;
use PHPUnit\Framework\Constraint\TraversableContainsOnly;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\Matcher\AnyInvokedCount as AnyInvokedCountMatcher;
use PHPUnit\Framework\MockObject\Matcher\InvokedAtIndex as InvokedAtIndexMatcher;
use PHPUnit\Framework\MockObject\Matcher\InvokedAtLeastCount as InvokedAtLeastCountMatcher;
use PHPUnit\Framework\MockObject\Matcher\InvokedAtLeastOnce as InvokedAtLeastOnceMatcher;
use PHPUnit\Framework\MockObject\Matcher\InvokedAtMostCount as InvokedAtMostCountMatcher;
use PHPUnit\Framework\MockObject\Matcher\InvokedCount as InvokedCountMatcher;
use PHPUnit\Framework\MockObject\Stub\ConsecutiveCalls as ConsecutiveCallsStub;
use PHPUnit\Framework\MockObject\Stub\Exception as ExceptionStub;
use PHPUnit\Framework\MockObject\Stub\ReturnArgument as ReturnArgumentStub;
use PHPUnit\Framework\MockObject\Stub\ReturnCallback as ReturnCallbackStub;
use PHPUnit\Framework\MockObject\Stub\ReturnSelf as ReturnSelfStub;
use PHPUnit\Framework\MockObject\Stub\ReturnStub;
use PHPUnit\Framework\MockObject\Stub\ReturnValueMap as ReturnValueMapStub;

/**
 * Asserts that an array has a specified key.
 *
 * @param int|string        $key
 * @param array|ArrayAccess $array
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertArrayHasKey($key, $array, string $message = ''): void
{
    Assert::assertArrayHasKey(...\func_get_args());
}

/**
 * Asserts that an array has a specified subset.
 *
 * @param array|ArrayAccess $subset
 * @param array|ArrayAccess $array
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 *
 * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3494
 */
function assertArraySubset($subset, $array, bool $checkForObjectIdentity = false, string $message = ''): void
{
    Assert::assertArraySubset(...\func_get_args());
}

/**
 * Asserts that an array does not have a specified key.
 *
 * @param int|string        $key
 * @param array|ArrayAccess $array
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertArrayNotHasKey($key, $array, string $message = ''): void
{
    Assert::assertArrayNotHasKey(...\func_get_args());
}

/**
 * Asserts that a haystack contains a needle.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertContains($needle, $haystack, string $message = '', bool $ignoreCase = false, bool $checkForObjectIdentity = true, bool $checkForNonObjectIdentity = false): void
{
    Assert::assertContains(...\func_get_args());
}

/**
 * Asserts that a haystack that is stored in a static attribute of a class
 * or an attribute of an object contains a needle.
 *
 * @param object|string $haystackClassOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeContains($needle, string $haystackAttributeName, $haystackClassOrObject, string $message = '', bool $ignoreCase = false, bool $checkForObjectIdentity = true, bool $checkForNonObjectIdentity = false): void
{
    Assert::assertAttributeContains(...\func_get_args());
}

/**
 * Asserts that a haystack does not contain a needle.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertNotContains($needle, $haystack, string $message = '', bool $ignoreCase = false, bool $checkForObjectIdentity = true, bool $checkForNonObjectIdentity = false): void
{
    Assert::assertNotContains(...\func_get_args());
}

/**
 * Asserts that a haystack that is stored in a static attribute of a class
 * or an attribute of an object does not contain a needle.
 *
 * @param object|string $haystackClassOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeNotContains($needle, string $haystackAttributeName, $haystackClassOrObject, string $message = '', bool $ignoreCase = false, bool $checkForObjectIdentity = true, bool $checkForNonObjectIdentity = false): void
{
    Assert::assertAttributeNotContains(...\func_get_args());
}

/**
 * Asserts that a haystack contains only values of a given type.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertContainsOnly(string $type, iterable $haystack, ?bool $isNativeType = null, string $message = ''): void
{
    Assert::assertContainsOnly(...\func_get_args());
}

/**
 * Asserts that a haystack contains only instances of a given class name.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertContainsOnlyInstancesOf(string $className, iterable $haystack, string $message = ''): void
{
    Assert::assertContainsOnlyInstancesOf(...\func_get_args());
}

/**
 * Asserts that a haystack that is stored in a static attribute of a class
 * or an attribute of an object contains only values of a given type.
 *
 * @param object|string $haystackClassOrObject
 * @param bool          $isNativeType
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeContainsOnly(string $type, string $haystackAttributeName, $haystackClassOrObject, ?bool $isNativeType = null, string $message = ''): void
{
    Assert::assertAttributeContainsOnly(...\func_get_args());
}

/**
 * Asserts that a haystack does not contain only values of a given type.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertNotContainsOnly(string $type, iterable $haystack, ?bool $isNativeType = null, string $message = ''): void
{
    Assert::assertNotContainsOnly(...\func_get_args());
}

/**
 * Asserts that a haystack that is stored in a static attribute of a class
 * or an attribute of an object does not contain only values of a given
 * type.
 *
 * @param object|string $haystackClassOrObject
 * @param bool          $isNativeType
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeNotContainsOnly(string $type, string $haystackAttributeName, $haystackClassOrObject, ?bool $isNativeType = null, string $message = ''): void
{
    Assert::assertAttributeNotContainsOnly(...\func_get_args());
}

/**
 * Asserts the number of elements of an array, Countable or Traversable.
 *
 * @param Countable|iterable $haystack
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertCount(int $expectedCount, $haystack, string $message = ''): void
{
    Assert::assertCount(...\func_get_args());
}

/**
 * Asserts the number of elements of an array, Countable or Traversable
 * that is stored in an attribute.
 *
 * @param object|string $haystackClassOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeCount(int $expectedCount, string $haystackAttributeName, $haystackClassOrObject, string $message = ''): void
{
    Assert::assertAttributeCount(...\func_get_args());
}

/**
 * Asserts the number of elements of an array, Countable or Traversable.
 *
 * @param Countable|iterable $haystack
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertNotCount(int $expectedCount, $haystack, string $message = ''): void
{
    Assert::assertNotCount(...\func_get_args());
}

/**
 * Asserts the number of elements of an array, Countable or Traversable
 * that is stored in an attribute.
 *
 * @param object|string $haystackClassOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeNotCount(int $expectedCount, string $haystackAttributeName, $haystackClassOrObject, string $message = ''): void
{
    Assert::assertAttributeNotCount(...\func_get_args());
}

/**
 * Asserts that two variables are equal.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertEquals($expected, $actual, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false): void
{
    Assert::assertEquals(...\func_get_args());
}

/**
 * Asserts that a variable is equal to an attribute of an object.
 *
 * @param object|string $actualClassOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeEquals($expected, string $actualAttributeName, $actualClassOrObject, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false): void
{
    Assert::assertAttributeEquals(...\func_get_args());
}

/**
 * Asserts that two variables are not equal.
 *
 * @param float $delta
 * @param int   $maxDepth
 * @param bool  $canonicalize
 * @param bool  $ignoreCase
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertNotEquals($expected, $actual, string $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false): void
{
    Assert::assertNotEquals(...\func_get_args());
}

/**
 * Asserts that a variable is not equal to an attribute of an object.
 *
 * @param object|string $actualClassOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeNotEquals($expected, string $actualAttributeName, $actualClassOrObject, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false): void
{
    Assert::assertAttributeNotEquals(...\func_get_args());
}

/**
 * Asserts that a variable is empty.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertEmpty($actual, string $message = ''): void
{
    Assert::assertEmpty(...\func_get_args());
}

/**
 * Asserts that a static attribute of a class or an attribute of an object
 * is empty.
 *
 * @param object|string $haystackClassOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeEmpty(string $haystackAttributeName, $haystackClassOrObject, string $message = ''): void
{
    Assert::assertAttributeEmpty(...\func_get_args());
}

/**
 * Asserts that a variable is not empty.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertNotEmpty($actual, string $message = ''): void
{
    Assert::assertNotEmpty(...\func_get_args());
}

/**
 * Asserts that a static attribute of a class or an attribute of an object
 * is not empty.
 *
 * @param object|string $haystackClassOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeNotEmpty(string $haystackAttributeName, $haystackClassOrObject, string $message = ''): void
{
    Assert::assertAttributeNotEmpty(...\func_get_args());
}

/**
 * Asserts that a value is greater than another value.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertGreaterThan($expected, $actual, string $message = ''): void
{
    Assert::assertGreaterThan(...\func_get_args());
}

/**
 * Asserts that an attribute is greater than another value.
 *
 * @param object|string $actualClassOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeGreaterThan($expected, string $actualAttributeName, $actualClassOrObject, string $message = ''): void
{
    Assert::assertAttributeGreaterThan(...\func_get_args());
}

/**
 * Asserts that a value is greater than or equal to another value.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertGreaterThanOrEqual($expected, $actual, string $message = ''): void
{
    Assert::assertGreaterThanOrEqual(...\func_get_args());
}

/**
 * Asserts that an attribute is greater than or equal to another value.
 *
 * @param object|string $actualClassOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeGreaterThanOrEqual($expected, string $actualAttributeName, $actualClassOrObject, string $message = ''): void
{
    Assert::assertAttributeGreaterThanOrEqual(...\func_get_args());
}

/**
 * Asserts that a value is smaller than another value.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertLessThan($expected, $actual, string $message = ''): void
{
    Assert::assertLessThan(...\func_get_args());
}

/**
 * Asserts that an attribute is smaller than another value.
 *
 * @param object|string $actualClassOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeLessThan($expected, string $actualAttributeName, $actualClassOrObject, string $message = ''): void
{
    Assert::assertAttributeLessThan(...\func_get_args());
}

/**
 * Asserts that a value is smaller than or equal to another value.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertLessThanOrEqual($expected, $actual, string $message = ''): void
{
    Assert::assertLessThanOrEqual(...\func_get_args());
}

/**
 * Asserts that an attribute is smaller than or equal to another value.
 *
 * @param object|string $actualClassOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeLessThanOrEqual($expected, string $actualAttributeName, $actualClassOrObject, string $message = ''): void
{
    Assert::assertAttributeLessThanOrEqual(...\func_get_args());
}

/**
 * Asserts that the contents of one file is equal to the contents of another
 * file.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertFileEquals(string $expected, string $actual, string $message = '', bool $canonicalize = false, bool $ignoreCase = false): void
{
    Assert::assertFileEquals(...\func_get_args());
}

/**
 * Asserts that the contents of one file is not equal to the contents of
 * another file.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertFileNotEquals(string $expected, string $actual, string $message = '', bool $canonicalize = false, bool $ignoreCase = false): void
{
    Assert::assertFileNotEquals(...\func_get_args());
}

/**
 * Asserts that the contents of a string is equal
 * to the contents of a file.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertStringEqualsFile(string $expectedFile, string $actualString, string $message = '', bool $canonicalize = false, bool $ignoreCase = false): void
{
    Assert::assertStringEqualsFile(...\func_get_args());
}

/**
 * Asserts that the contents of a string is not equal
 * to the contents of a file.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertStringNotEqualsFile(string $expectedFile, string $actualString, string $message = '', bool $canonicalize = false, bool $ignoreCase = false): void
{
    Assert::assertStringNotEqualsFile(...\func_get_args());
}

/**
 * Asserts that a file/dir is readable.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertIsReadable(string $filename, string $message = ''): void
{
    Assert::assertIsReadable(...\func_get_args());
}

/**
 * Asserts that a file/dir exists and is not readable.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertNotIsReadable(string $filename, string $message = ''): void
{
    Assert::assertNotIsReadable(...\func_get_args());
}

/**
 * Asserts that a file/dir exists and is writable.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertIsWritable(string $filename, string $message = ''): void
{
    Assert::assertIsWritable(...\func_get_args());
}

/**
 * Asserts that a file/dir exists and is not writable.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertNotIsWritable(string $filename, string $message = ''): void
{
    Assert::assertNotIsWritable(...\func_get_args());
}

/**
 * Asserts that a directory exists.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertDirectoryExists(string $directory, string $message = ''): void
{
    Assert::assertDirectoryExists(...\func_get_args());
}

/**
 * Asserts that a directory does not exist.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertDirectoryNotExists(string $directory, string $message = ''): void
{
    Assert::assertDirectoryNotExists(...\func_get_args());
}

/**
 * Asserts that a directory exists and is readable.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertDirectoryIsReadable(string $directory, string $message = ''): void
{
    Assert::assertDirectoryIsReadable(...\func_get_args());
}

/**
 * Asserts that a directory exists and is not readable.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertDirectoryNotIsReadable(string $directory, string $message = ''): void
{
    Assert::assertDirectoryNotIsReadable(...\func_get_args());
}

/**
 * Asserts that a directory exists and is writable.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertDirectoryIsWritable(string $directory, string $message = ''): void
{
    Assert::assertDirectoryIsWritable(...\func_get_args());
}

/**
 * Asserts that a directory exists and is not writable.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertDirectoryNotIsWritable(string $directory, string $message = ''): void
{
    Assert::assertDirectoryNotIsWritable(...\func_get_args());
}

/**
 * Asserts that a file exists.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertFileExists(string $filename, string $message = ''): void
{
    Assert::assertFileExists(...\func_get_args());
}

/**
 * Asserts that a file does not exist.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertFileNotExists(string $filename, string $message = ''): void
{
    Assert::assertFileNotExists(...\func_get_args());
}

/**
 * Asserts that a file exists and is readable.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertFileIsReadable(string $file, string $message = ''): void
{
    Assert::assertFileIsReadable(...\func_get_args());
}

/**
 * Asserts that a file exists and is not readable.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertFileNotIsReadable(string $file, string $message = ''): void
{
    Assert::assertFileNotIsReadable(...\func_get_args());
}

/**
 * Asserts that a file exists and is writable.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertFileIsWritable(string $file, string $message = ''): void
{
    Assert::assertFileIsWritable(...\func_get_args());
}

/**
 * Asserts that a file exists and is not writable.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertFileNotIsWritable(string $file, string $message = ''): void
{
    Assert::assertFileNotIsWritable(...\func_get_args());
}

/**
 * Asserts that a condition is true.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertTrue($condition, string $message = ''): void
{
    Assert::assertTrue(...\func_get_args());
}

/**
 * Asserts that a condition is not true.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertNotTrue($condition, string $message = ''): void
{
    Assert::assertNotTrue(...\func_get_args());
}

/**
 * Asserts that a condition is false.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertFalse($condition, string $message = ''): void
{
    Assert::assertFalse(...\func_get_args());
}

/**
 * Asserts that a condition is not false.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertNotFalse($condition, string $message = ''): void
{
    Assert::assertNotFalse(...\func_get_args());
}

/**
 * Asserts that a variable is null.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertNull($actual, string $message = ''): void
{
    Assert::assertNull(...\func_get_args());
}

/**
 * Asserts that a variable is not null.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertNotNull($actual, string $message = ''): void
{
    Assert::assertNotNull(...\func_get_args());
}

/**
 * Asserts that a variable is finite.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertFinite($actual, string $message = ''): void
{
    Assert::assertFinite(...\func_get_args());
}

/**
 * Asserts that a variable is infinite.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertInfinite($actual, string $message = ''): void
{
    Assert::assertInfinite(...\func_get_args());
}

/**
 * Asserts that a variable is nan.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertNan($actual, string $message = ''): void
{
    Assert::assertNan(...\func_get_args());
}

/**
 * Asserts that a class has a specified attribute.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertClassHasAttribute(string $attributeName, string $className, string $message = ''): void
{
    Assert::assertClassHasAttribute(...\func_get_args());
}

/**
 * Asserts that a class does not have a specified attribute.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertClassNotHasAttribute(string $attributeName, string $className, string $message = ''): void
{
    Assert::assertClassNotHasAttribute(...\func_get_args());
}

/**
 * Asserts that a class has a specified static attribute.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertClassHasStaticAttribute(string $attributeName, string $className, string $message = ''): void
{
    Assert::assertClassHasStaticAttribute(...\func_get_args());
}

/**
 * Asserts that a class does not have a specified static attribute.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertClassNotHasStaticAttribute(string $attributeName, string $className, string $message = ''): void
{
    Assert::assertClassNotHasStaticAttribute(...\func_get_args());
}

/**
 * Asserts that an object has a specified attribute.
 *
 * @param object $object
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertObjectHasAttribute(string $attributeName, $object, string $message = ''): void
{
    Assert::assertObjectHasAttribute(...\func_get_args());
}

/**
 * Asserts that an object does not have a specified attribute.
 *
 * @param object $object
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertObjectNotHasAttribute(string $attributeName, $object, string $message = ''): void
{
    Assert::assertObjectNotHasAttribute(...\func_get_args());
}

/**
 * Asserts that two variables have the same type and value.
 * Used on objects, it asserts that two variables reference
 * the same object.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertSame($expected, $actual, string $message = ''): void
{
    Assert::assertSame(...\func_get_args());
}

/**
 * Asserts that a variable and an attribute of an object have the same type
 * and value.
 *
 * @param object|string $actualClassOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeSame($expected, string $actualAttributeName, $actualClassOrObject, string $message = ''): void
{
    Assert::assertAttributeSame(...\func_get_args());
}

/**
 * Asserts that two variables do not have the same type and value.
 * Used on objects, it asserts that two variables do not reference
 * the same object.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertNotSame($expected, $actual, string $message = ''): void
{
    Assert::assertNotSame(...\func_get_args());
}

/**
 * Asserts that a variable and an attribute of an object do not have the
 * same type and value.
 *
 * @param object|string $actualClassOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeNotSame($expected, string $actualAttributeName, $actualClassOrObject, string $message = ''): void
{
    Assert::assertAttributeNotSame(...\func_get_args());
}

/**
 * Asserts that a variable is of a given type.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertInstanceOf(string $expected, $actual, string $message = ''): void
{
    Assert::assertInstanceOf(...\func_get_args());
}

/**
 * Asserts that an attribute is of a given type.
 *
 * @param object|string $classOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeInstanceOf(string $expected, string $attributeName, $classOrObject, string $message = ''): void
{
    Assert::assertAttributeInstanceOf(...\func_get_args());
}

/**
 * Asserts that a variable is not of a given type.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertNotInstanceOf(string $expected, $actual, string $message = ''): void
{
    Assert::assertNotInstanceOf(...\func_get_args());
}

/**
 * Asserts that an attribute is of a given type.
 *
 * @param object|string $classOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeNotInstanceOf(string $expected, string $attributeName, $classOrObject, string $message = ''): void
{
    Assert::assertAttributeNotInstanceOf(...\func_get_args());
}

/**
 * Asserts that a variable is of a given type.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertInternalType(string $expected, $actual, string $message = ''): void
{
    Assert::assertInternalType(...\func_get_args());
}

/**
 * Asserts that an attribute is of a given type.
 *
 * @param object|string $classOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeInternalType(string $expected, string $attributeName, $classOrObject, string $message = ''): void
{
    Assert::assertAttributeInternalType(...\func_get_args());
}

/**
 * Asserts that a variable is not of a given type.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertNotInternalType(string $expected, $actual, string $message = ''): void
{
    Assert::assertNotInternalType(...\func_get_args());
}

/**
 * Asserts that an attribute is of a given type.
 *
 * @param object|string $classOrObject
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertAttributeNotInternalType(string $expected, string $attributeName, $classOrObject, string $message = ''): void
{
    Assert::assertAttributeNotInternalType(...\func_get_args());
}

/**
 * Asserts that a string matches a given regular expression.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertRegExp(string $pattern, string $string, string $message = ''): void
{
    Assert::assertRegExp(...\func_get_args());
}

/**
 * Asserts that a string does not match a given regular expression.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertNotRegExp(string $pattern, string $string, string $message = ''): void
{
    Assert::assertNotRegExp(...\func_get_args());
}

/**
 * Assert that the size of two arrays (or `Countable` or `Traversable` objects)
 * is the same.
 *
 * @param Countable|iterable $expected
 * @param Countable|iterable $actual
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertSameSize($expected, $actual, string $message = ''): void
{
    Assert::assertSameSize(...\func_get_args());
}

/**
 * Assert that the size of two arrays (or `Countable` or `Traversable` objects)
 * is not the same.
 *
 * @param Countable|iterable $expected
 * @param Countable|iterable $actual
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertNotSameSize($expected, $actual, string $message = ''): void
{
    Assert::assertNotSameSize(...\func_get_args());
}

/**
 * Asserts that a string matches a given format string.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertStringMatchesFormat(string $format, string $string, string $message = ''): void
{
    Assert::assertStringMatchesFormat(...\func_get_args());
}

/**
 * Asserts that a string does not match a given format string.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertStringNotMatchesFormat(string $format, string $string, string $message = ''): void
{
    Assert::assertStringNotMatchesFormat(...\func_get_args());
}

/**
 * Asserts that a string matches a given format file.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertStringMatchesFormatFile(string $formatFile, string $string, string $message = ''): void
{
    Assert::assertStringMatchesFormatFile(...\func_get_args());
}

/**
 * Asserts that a string does not match a given format string.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertStringNotMatchesFormatFile(string $formatFile, string $string, string $message = ''): void
{
    Assert::assertStringNotMatchesFormatFile(...\func_get_args());
}

/**
 * Asserts that a string starts with a given prefix.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertStringStartsWith(string $prefix, string $string, string $message = ''): void
{
    Assert::assertStringStartsWith(...\func_get_args());
}

/**
 * Asserts that a string starts not with a given prefix.
 *
 * @param string $prefix
 * @param string $string
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertStringStartsNotWith($prefix, $string, string $message = ''): void
{
    Assert::assertStringStartsNotWith(...\func_get_args());
}

/**
 * Asserts that a string ends with a given suffix.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertStringEndsWith(string $suffix, string $string, string $message = ''): void
{
    Assert::assertStringEndsWith(...\func_get_args());
}

/**
 * Asserts that a string ends not with a given suffix.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertStringEndsNotWith(string $suffix, string $string, string $message = ''): void
{
    Assert::assertStringEndsNotWith(...\func_get_args());
}

/**
 * Asserts that two XML files are equal.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertXmlFileEqualsXmlFile(string $expectedFile, string $actualFile, string $message = ''): void
{
    Assert::assertXmlFileEqualsXmlFile(...\func_get_args());
}

/**
 * Asserts that two XML files are not equal.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertXmlFileNotEqualsXmlFile(string $expectedFile, string $actualFile, string $message = ''): void
{
    Assert::assertXmlFileNotEqualsXmlFile(...\func_get_args());
}

/**
 * Asserts that two XML documents are equal.
 *
 * @param DOMDocument|string $actualXml
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertXmlStringEqualsXmlFile(string $expectedFile, $actualXml, string $message = ''): void
{
    Assert::assertXmlStringEqualsXmlFile(...\func_get_args());
}

/**
 * Asserts that two XML documents are not equal.
 *
 * @param DOMDocument|string $actualXml
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertXmlStringNotEqualsXmlFile(string $expectedFile, $actualXml, string $message = ''): void
{
    Assert::assertXmlStringNotEqualsXmlFile(...\func_get_args());
}

/**
 * Asserts that two XML documents are equal.
 *
 * @param DOMDocument|string $expectedXml
 * @param DOMDocument|string $actualXml
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertXmlStringEqualsXmlString($expectedXml, $actualXml, string $message = ''): void
{
    Assert::assertXmlStringEqualsXmlString(...\func_get_args());
}

/**
 * Asserts that two XML documents are not equal.
 *
 * @param DOMDocument|string $expectedXml
 * @param DOMDocument|string $actualXml
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertXmlStringNotEqualsXmlString($expectedXml, $actualXml, string $message = ''): void
{
    Assert::assertXmlStringNotEqualsXmlString(...\func_get_args());
}

/**
 * Asserts that a hierarchy of DOMElements matches.
 *
 * @throws AssertionFailedError
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertEqualXMLStructure(DOMElement $expectedElement, DOMElement $actualElement, bool $checkAttributes = false, string $message = ''): void
{
    Assert::assertEqualXMLStructure(...\func_get_args());
}

/**
 * Evaluates a PHPUnit\Framework\Constraint matcher object.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertThat($value, Constraint $constraint, string $message = ''): void
{
    Assert::assertThat(...\func_get_args());
}

/**
 * Asserts that a string is a valid JSON string.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertJson(string $actualJson, string $message = ''): void
{
    Assert::assertJson(...\func_get_args());
}

/**
 * Asserts that two given JSON encoded objects or arrays are equal.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertJsonStringEqualsJsonString(string $expectedJson, string $actualJson, string $message = ''): void
{
    Assert::assertJsonStringEqualsJsonString(...\func_get_args());
}

/**
 * Asserts that two given JSON encoded objects or arrays are not equal.
 *
 * @param string $expectedJson
 * @param string $actualJson
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertJsonStringNotEqualsJsonString($expectedJson, $actualJson, string $message = ''): void
{
    Assert::assertJsonStringNotEqualsJsonString(...\func_get_args());
}

/**
 * Asserts that the generated JSON encoded object and the content of the given file are equal.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertJsonStringEqualsJsonFile(string $expectedFile, string $actualJson, string $message = ''): void
{
    Assert::assertJsonStringEqualsJsonFile(...\func_get_args());
}

/**
 * Asserts that the generated JSON encoded object and the content of the given file are not equal.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertJsonStringNotEqualsJsonFile(string $expectedFile, string $actualJson, string $message = ''): void
{
    Assert::assertJsonStringNotEqualsJsonFile(...\func_get_args());
}

/**
 * Asserts that two JSON files are equal.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertJsonFileEqualsJsonFile(string $expectedFile, string $actualFile, string $message = ''): void
{
    Assert::assertJsonFileEqualsJsonFile(...\func_get_args());
}

/**
 * Asserts that two JSON files are not equal.
 *
 * @throws ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 */
function assertJsonFileNotEqualsJsonFile(string $expectedFile, string $actualFile, string $message = ''): void
{
    Assert::assertJsonFileNotEqualsJsonFile(...\func_get_args());
}

function logicalAnd(): LogicalAnd
{
    return Assert::logicalAnd(...\func_get_args());
}

function logicalOr(): LogicalOr
{
    return Assert::logicalOr(...\func_get_args());
}

function logicalNot(Constraint $constraint): LogicalNot
{
    return Assert::logicalNot(...\func_get_args());
}

function logicalXor(): LogicalXor
{
    return Assert::logicalXor(...\func_get_args());
}

function anything(): IsAnything
{
    return Assert::anything();
}

function isTrue(): IsTrue
{
    return Assert::isTrue();
}

function callback(callable $callback): Callback
{
    return Assert::callback(...\func_get_args());
}

function isFalse(): IsFalse
{
    return Assert::isFalse();
}

function isJson(): IsJson
{
    return Assert::isJson();
}

function isNull(): IsNull
{
    return Assert::isNull();
}

function isFinite(): IsFinite
{
    return Assert::isFinite();
}

function isInfinite(): IsInfinite
{
    return Assert::isInfinite();
}

function isNan(): IsNan
{
    return Assert::isNan();
}

function attribute(Constraint $constraint, string $attributeName): Attribute
{
    return Assert::attribute(...\func_get_args());
}

function contains($value, bool $checkForObjectIdentity = true, bool $checkForNonObjectIdentity = false): TraversableContains
{
    return Assert::contains(...\func_get_args());
}

function containsOnly(string $type): TraversableContainsOnly
{
    return Assert::containsOnly(...\func_get_args());
}

function containsOnlyInstancesOf(string $className): TraversableContainsOnly
{
    return Assert::containsOnlyInstancesOf(...\func_get_args());
}

function arrayHasKey($key): ArrayHasKey
{
    return Assert::arrayHasKey(...\func_get_args());
}

function equalTo($value, float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false): IsEqual
{
    return Assert::equalTo(...\func_get_args());
}

function attributeEqualTo(string $attributeName, $value, float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false): Attribute
{
    return Assert::attributeEqualTo(...\func_get_args());
}

function isEmpty(): IsEmpty
{
    return Assert::isEmpty();
}

function isWritable(): IsWritable
{
    return Assert::isWritable();
}

function isReadable(): IsReadable
{
    return Assert::isReadable();
}

function directoryExists(): DirectoryExists
{
    return Assert::directoryExists();
}

function fileExists(): FileExists
{
    return Assert::fileExists();
}

function greaterThan($value): GreaterThan
{
    return Assert::greaterThan(...\func_get_args());
}

function greaterThanOrEqual($value): LogicalOr
{
    return Assert::greaterThanOrEqual(...\func_get_args());
}

function classHasAttribute(string $attributeName): ClassHasAttribute
{
    return Assert::classHasAttribute(...\func_get_args());
}

function classHasStaticAttribute(string $attributeName): ClassHasStaticAttribute
{
    return Assert::classHasStaticAttribute(...\func_get_args());
}

function objectHasAttribute($attributeName): ObjectHasAttribute
{
    return Assert::objectHasAttribute(...\func_get_args());
}

function identicalTo($value): IsIdentical
{
    return Assert::identicalTo(...\func_get_args());
}

function isInstanceOf(string $className): IsInstanceOf
{
    return Assert::isInstanceOf(...\func_get_args());
}

function isType(string $type): IsType
{
    return Assert::isType(...\func_get_args());
}

function lessThan($value): LessThan
{
    return Assert::lessThan(...\func_get_args());
}

function lessThanOrEqual($value): LogicalOr
{
    return Assert::lessThanOrEqual(...\func_get_args());
}

function matchesRegularExpression(string $pattern): RegularExpression
{
    return Assert::matchesRegularExpression(...\func_get_args());
}

function matches(string $string): StringMatchesFormatDescription
{
    return Assert::matches(...\func_get_args());
}

function stringStartsWith($prefix): StringStartsWith
{
    return Assert::stringStartsWith(...\func_get_args());
}

function stringContains(string $string, bool $case = true): StringContains
{
    return Assert::stringContains(...\func_get_args());
}

function stringEndsWith(string $suffix): StringEndsWith
{
    return Assert::stringEndsWith(...\func_get_args());
}

function countOf(int $count): Count
{
    return Assert::countOf(...\func_get_args());
}

/**
 * Returns a matcher that matches when the method is executed
 * zero or more times.
 */
function any(): AnyInvokedCountMatcher
{
    return new AnyInvokedCountMatcher;
}

/**
 * Returns a matcher that matches when the method is never executed.
 */
function never(): InvokedCountMatcher
{
    return new InvokedCountMatcher(0);
}

/**
 * Returns a matcher that matches when the method is executed
 * at least N times.
 *
 * @param int $requiredInvocations
 */
function atLeast($requiredInvocations): InvokedAtLeastCountMatcher
{
    return new InvokedAtLeastCountMatcher(
        $requiredInvocations
    );
}

/**
 * Returns a matcher that matches when the method is executed at least once.
 */
function atLeastOnce(): InvokedAtLeastOnceMatcher
{
    return new InvokedAtLeastOnceMatcher;
}

/**
 * Returns a matcher that matches when the method is executed exactly once.
 */
function once(): InvokedCountMatcher
{
    return new InvokedCountMatcher(1);
}

/**
 * Returns a matcher that matches when the method is executed
 * exactly $count times.
 *
 * @param int $count
 */
function exactly($count): InvokedCountMatcher
{
    return new InvokedCountMatcher($count);
}

/**
 * Returns a matcher that matches when the method is executed
 * at most N times.
 *
 * @param int $allowedInvocations
 */
function atMost($allowedInvocations): InvokedAtMostCountMatcher
{
    return new InvokedAtMostCountMatcher($allowedInvocations);
}

/**
 * Returns a matcher that matches when the method is executed
 * at the given index.
 *
 * @param int $index
 */
function at($index): InvokedAtIndexMatcher
{
    return new InvokedAtIndexMatcher($index);
}

function returnValue($value): ReturnStub
{
    return new ReturnStub($value);
}

function returnValueMap(array $valueMap): ReturnValueMapStub
{
    return new ReturnValueMapStub($valueMap);
}

/**
 * @param int $argumentIndex
 */
function returnArgument($argumentIndex): ReturnArgumentStub
{
    return new ReturnArgumentStub($argumentIndex);
}

function returnCallback($callback): ReturnCallbackStub
{
    return new ReturnCallbackStub($callback);
}

/**
 * Returns the current object.
 *
 * This method is useful when mocking a fluent interface.
 */
function returnSelf(): ReturnSelfStub
{
    return new ReturnSelfStub;
}

function throwException(Throwable $exception): ExceptionStub
{
    return new ExceptionStub($exception);
}

function onConsecutiveCalls(): ConsecutiveCallsStub
{
    $args = \func_get_args();

    return new ConsecutiveCallsStub($args);
}
                                                                                                                                                                                                            <?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework\Constraint;

use ArrayAccess;

/**
 * Constraint that asserts that the array it is evaluated for has a given key.
 *
 * Uses array_key_exists() to check if the key is found in the input array, if
 * not found the evaluation fails.
 *
 * The array key is passed in the constructor.
 */
class ArrayHasKey extends Constraint
{
    /**
     * @var int|string
     */
    private $key;

    /**
     * @param int|string $key
     */
    public function __construct($key)
    {
        parent::__construct();
        $this->key = $key;
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function toString(): string
    {
        return 'has the key ' . $this->exporter->export($this->key);
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other value or object to evaluate
     */
    protected function matches($other): bool
    {
        if (\is_array($other)) {
            return \array_key_exists($this->key, $other);
        }

        if ($other instanceof ArrayAccess) {
            return $other->offsetExists($this->key);
        }

        return false;
    }

    /**
     * Returns the description of the failure
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param mixed $other evaluated value or object
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    protected function failureDescription($other): string
    {
        return 'an array ' . $this->toString();
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         <?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework\Constraint;

use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * Constraint that asserts that the array it is evaluated for has a specified subset.
 *
 * Uses array_replace_recursive() to check if a key value subset is part of the
 * subject array.
 *
 * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3494
 */
class ArraySubset extends Constraint
{
    /**
     * @var iterable
     */
    private $subset;

    /**
     * @var bool
 