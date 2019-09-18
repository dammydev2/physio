 );
            }

            if (isset($annotations['method']['expectedExceptionMessageRegExp'])) {
                $messageRegExp = self::parseAnnotationContent(
                    $annotations['method']['expectedExceptionMessageRegExp'][0]
                );
            }

            if (isset($matches[3])) {
                $code = $matches[3];
            } elseif (isset($annotations['method']['expectedExceptionCode'])) {
                $code = self::parseAnnotationContent(
                    $annotations['method']['expectedExceptionCode'][0]
                );
            }

            if (\is_numeric($code)) {
                $code = (int) $code;
            } elseif (\is_string($code) && \defined($code)) {
                $code = (int) \constant($code);
            }

            return [
                'class' => $class, 'code' => $code, 'message' => $message, 'message_regex' => $messageRegExp,
            ];
        }

        return false;
    }

    /**
     * Returns the provided data for a method.
     *
     * @throws Exception
     */
    public static function getProvidedData(string $className, string $methodName): ?array
    {
        $reflector  = new ReflectionMethod($className, $methodName);
        $docComment = $reflector->getDocComment();

        $data = self::getDataFromDataProviderAnnotation($docComment, $className, $methodName);

        if ($data === null) {
            $data = self::getDataFromTestWithAnnotation($docComment);
        }

        if ($data === []) {
            throw new SkippedTestError;
        }

        if ($data !== null) {
            foreach ($data as $key => $value) {
                if (!\is_array($value)) {
                    throw new Exception(
                        \sprintf(
                            'Data set %s is invalid.',
                            \is_int($key) ? '#' . $key : '"' . $key . '"'
                        )
                    );
                }
            }
        }

        return $data;
    }

    /**
     * @throws Exception
     */
    public static function getDataFromTestWithAnnotation(string $docComment): ?array
    {
        $docComment = self::cleanUpMultiLineAnnotation($docComment);

        if (\preg_match(self::REGEX_TEST_WITH, $docComment, $matches, \PREG_OFFSET_CAPTURE)) {
            $offset            = \strlen($matches[0][0]) + $matches[0][1];
            $annotationContent = \substr($docComment, $offset);
            $data              = [];

            foreach (\explode("\n", $annotationContent) as $candidateRow) {
                $candidateRow = \trim($candidateRow);

                if ($candidateRow[0] !== '[') {
                    break;
                }

                $dataSet = \json_decode($candidateRow, true);

                if (\json_last_error() !== \JSON_ERROR_NONE) {
                    throw new Exception(
                        'The data set for the @testWith annotation cannot be parsed: ' . \json_last_error_msg()
                    );
                }

                $data[] = $dataSet;
            }

            if (!$data) {
                throw new Exception('The data set for the @testWith annotation cannot be parsed.');
            }

            return $data;
        }

        return null;
    }

    public static function parseTestMethodAnnotations(string $className, ?string $methodName = ''): array
    {
        if (!isset(self::$annotationCache[$className])) {
            $class       = new ReflectionClass($className);
            $traits      = $class->getTraits();
            $annotations = [];

            foreach ($traits as $trait) {
                $annotations = \array_merge(
                    $annotations,
                    self::parseAnnotations($trait->getDocComment())
                );
            }

            self::$annotationCache[$className] = \array_merge(
                $annotations,
                self::parseAnnotations($class->getDocComment())
            );
        }

        $cacheKey = $className . '::' . $methodName;

        if ($methodName !== null && !isset(self::$annotationCache[$cacheKey])) {
            try {
                $method      = new ReflectionMethod($className, $methodName);
                $annotations = self::parseAnnotations($method->getDocComment());
            } catch (ReflectionException $e) {
                $annotations = [];
            }

            self::$annotationCache[$cacheKey] = $annotations;
        }

        return [
            'class'  => self::$annotationCache[$className],
            'method' => $methodName !== null ? self::$annotationCache[$cacheKey] : [],
        ];
    }

    public static function getInlineAnnotations(string $className, string $methodName): array
    {
        $method      = new ReflectionMethod($className, $methodName);
        $code        = \file($method->getFileName());
        $lineNumber  = $method->getStartLine();
        $startLine   = $method->getStartLine() - 1;
        $endLine     = $method->getEndLine() - 1;
        $methodLines = \array_slice($code, $startLine, $endLine - $startLine + 1);
        $annotations = [];

        foreach ($methodLines as $line) {
            if (\preg_match('#/\*\*?\s*@(?P<name>[A-Za-z_-]+)(?:[ \t]+(?P<value>.*?))?[ \t]*\r?\*/$#m', $line, $matches)) {
                $annotations[\strtolower($matches['name'])] = [
                    'line'  => $lineNumber,
                    'value' => $matches['value'],
                ];
            }

            $lineNumber++;
        }

        return $annotations;
    }

    public static function parseAnnotations(string $docBlock): array
    {
        $annotations = [];
        // Strip away the docblock header and footer to ease parsing of one line annotations
        $docBlock = \substr($docBlock, 3, -2);

        if (\preg_match_all('/@(?P<name>[A-Za-z_-]+)(?:[ \t]+(?P<value>.*?))?[ \t]*\r?$/m', $docBlock, $matches)) {
            $numMatches = \count($matches[0]);

            for ($i = 0; $i < $numMatches; ++$i) {
                $annotations[$matches['name'][$i]][] = (string) $matches['value'][$i];
            }
        }

        return $annotations;
    }

    public static function getBackupSettings(string $className, string $methodName): array
    {
        return [
            'backupGlobals' => self::getBooleanAnnotationSetting(
                $className,
                $methodName,
                'backupGlobals'
            ),
            'backupStaticAttributes' => self::getBooleanAnnotationSetting(
                $className,
                $methodName,
                'backupStaticAttributes'
            ),
        ];
    }

    public static function getDependencies(string $className, string $methodName): array
    {
        $annotations = self::parseTestMethodAnnotations(
            $className,
            $methodName
        );

        $dependencies = [];

        if (isset($annotations['class']['depends'])) {
            $dependencies = $annotations['class']['depends'];
        }

        if (isset($annotations['method']['depends'])) {
            $dependencies = \array_merge(
                $dependencies,
                $annotations['method']['depends']
            );
        }

        return \array_unique($dependencies);
    }

    public static function getErrorHandlerSettings(string $className, ?string $methodName): ?bool
    {
        return self::getBooleanAnnotationSetting(
            $className,
            $methodName,
            'errorHandler'
        );
    }

    public static function getGroups(string $className, ?string $methodName = ''): array
    {
        $annotations = self::parseTestMethodAnnotations(
            $className,
            $methodName
        );

        $groups = [];

        if (isset($annotations['method']['author'])) {
            $groups = $annotations['method']['author'];
        } elseif (isset($annotations['class']['author'])) {
            $groups = $annotations['class']['author'];
        }

        if (isset($annotations['class']['group'])) {
            $groups = \array_merge($groups, $annotations['class']['group']);
        }

        if (isset($annotations['method']['group'])) {
            $groups = \array_merge($groups, $annotations['method']['group']);
        }

        if (isset($annotations['class']['ticket'])) {
            $groups = \array_merge($groups, $annotations['class']['ticket']);
        }

        if (isset($annotations['method']['ticket'])) {
            $groups = \array_merge($groups, $annotations['method']['ticket']);
        }

        foreach (['method', 'class'] as $element) {
            foreach (['small', 'medium', 'large'] as $size) {
                if (isset($annotations[$element][$size])) {
                    $groups[] = $size;

                    break 2;
                }
            }
        }

        return \array_unique($groups);
    }

    public static function getSize(string $className, ?string $methodName): int
    {
        $groups = \array_flip(self::getGroups($className, $methodName));

        if (isset($groups['large'])) {
            return self::LARGE;
        }

        if (isset($groups['medium'])) {
            return self::MEDIUM;
        }

        if (isset($groups['small'])) {
            return self::SMALL;
        }

        return self::UNKNOWN;
    }

    public static function getProcessIsolationSettings(string $className, string $methodName): bool
    {
        $annotations = self::parseTestMethodAnnotations(
            $className,
            $methodName
        );

        return isset($annotations['class']['runTestsInSeparateProcesses']) || isset($annotations['method']['runInSeparateProcess']);
    }

    public static function getClassProcessIsolationSettings(string $className, string $methodName): bool
    {
        $annotations = self::parseTestMethodAnnotations(
            $className,
            $methodName
        );

        return isset($annotations['class']['runClassInSeparateProcess']);
    }

    public static function getPreserveGlobalStateSettings(string $className, string $methodName): ?bool
    {
        return self::getBooleanAnnotationSetting(
            $className,
            $methodName,
            'preserveGlobalState'
        );
    }

    public static function getHookMethods(string $className): array
    {
        if (!\class_exists($className, false)) {
            return self::emptyHookMethodsArray();
        }

        if (!isset(self::$hookMethods[$className])) {
            self::$hookMethods[$className] = self::emptyHookMethodsArray();

            try {
                $class = new ReflectionClass($className);

                foreach ($class->getMethods() as $method) {
                    if ($method->getDeclaringClass()->getName() === Assert::class) {
                        continue;
                    }

                    if ($method->getDeclaringClass()->getName() === TestCase::class) {
                        continue;
                    }

                    if ($methodComment = $method->getDocComment()) {
                        if ($method->isStatic()) {
                            if (\strpos($methodComment, '@beforeClass') !== false) {
                                \array_unshift(
                                    self::$hookMethods[$className]['beforeClass'],
                                    $method->getName()
                                );
                            }

                            if (\strpos($methodComment, '@afterClass') !== false) {
                                self::$hookMethods[$className]['afterClass'][] = $method->getName();
                            }
                        }

                        if (\preg_match('/@before\b/', $methodComment) > 0) {
                            \array_unshift(
                                self::$hookMethods[$className]['before'],
                                $method->getName()
                            );
                        }

                        if (\preg_match('/@after\b/', $methodComment) > 0) {
                            self::$hookMethods[$className]['after'][] = $method->getName();
                        }
                    }
                }
            } catch (ReflectionException $e) {
            }
        }

        return self::$hookMethods[$className];
    }

    /**
     * @throws CodeCoverageException
     */
    private static function getLinesToBeCoveredOrUsed(string $className, string $methodName, string $mode): array
    {
        $annotations = self::parseTestMethodAnnotations(
            $className,
            $methodName
        );

        $classShortcut = null;

        if (!empty($annotations['class'][$mode . 'DefaultClass'])) {
            if (\count($annotations['class'][$mode . 'DefaultClass']) > 1) {
                throw new CodeCoverageException(
                    \sprintf(
                        'More than one @%sClass annotation in class or interface "%s".',
                        $mode,
                        $className
                    )
                );
            }

            $classShortcut = $annotations['class'][$mode . 'DefaultClass'][0];
        }

        $list = [];

        if (isset($annotations['class'][$mode])) {
            $list = $annotations['class'][$mode];
        }

        if (isset($annotations['method'][$mode])) {
            $list = \array_merge($list, $annotations['method'][$mode]);
        }

        $codeList = [];

        foreach (\array_unique($list) as $element) {
            if ($classShortcut && \strncmp($element, '::', 2) === 0) {
                $element = $classShortcut . $element;
            }

            $element = \preg_replace('/[\s()]+$/', '', $element);
            $element = \explode(' ', $element);
            $element = $element[0];

            if ($mode === 'covers' && \interface_exists($element)) {
                throw new InvalidCoversTargetException(
                    \sprintf(
                        'Trying to @cover interface "%s".',
                        $element
                    )
                );
            }

            $codeList = \array_merge(
                $codeList,
                self::resolveElementToReflectionObjects($element)
            );
        }

        return self::resolveReflectionObjectsToLines($codeList);
    }

    /**
     * Parse annotation content to use constant/class constant values
     *
     * Constants are specified using a starting '@'. For example: @ClassName::CONST_NAME
     *
     * If the constant is not found the string is used as is to ensure maximum BC.
     */
    private static function parseAnnotationContent(string $message): string
    {
        if (\defined($message) && (\strpos($message, '::') !== false && \substr_count($message, '::') + 1 === 2)) {
            $message = \constant($message);
        }

        return $message;
    }

    /**
     * Returns the provided data for a method.
     */
    private static function getDataFromDataProviderAnnotation(string $docComment, string $className, string $methodName): ?iterable
    {
        if (\preg_match_all(self::REGEX_DATA_PROVIDER, $docComment, $matches)) {
            $result = [];

            foreach ($matches[1] as $match) {
                $dataProviderMethodNameNamespace = \explode('\\', $match);
                $leaf                            = \explode('::', \array_pop($dataProviderMethodNameNamespace));
                $dataProviderMethodName          = \array_pop($leaf);

                if (empty($dataProviderMethodNameNamespace)) {
                    $dataProviderMethodNameNamespace = '';
                } else {
                    $dataProviderMethodNameNamespace = \implode('\\', $dataProviderMethodNameNamespace) . '\\';
                }

                if (empty($leaf)) {
                    $dataProviderClassName = $className;
                } else {
                    $dataProviderClassName = $dataProviderMethodNameNamespace . \array_pop($leaf);
                }

                $dataProviderClass  = new ReflectionClass($dataProviderClassName);
                $dataProviderMethod = $dataProviderClass->getMethod(
                    $dataProviderMethodName
                );

                if ($dataProviderMethod->isStatic()) {
                    $object = null;
                } else {
                    $object = $dataProviderClass->newInstance();
                }

                if ($dataProviderMethod->getNumberOfParameters() === 0) {
                    $data = $dataProviderMethod->invoke($object);
                } else {
                    $data = $dataProviderMethod->invoke($object, $methodName);
                }

                if ($data instanceof Traversable) {
                    $origData = $data;
                    $data     = [];

                    foreach ($origData as $key => $value) {
                        if (\is_int($key)) {
                            $data[] = $value;
                        } else {
                            $data[$key] = $value;
                        }
                    }
                }

                if (\is_array($data)) {
                    $result = \array_merge($result, $data);
                }
            }

            return $result;
        }

        return null;
    }

    private static function cleanUpMultiLineAnnotation(string $docComment): string
    {
        //removing initial '   * ' for docComment
        $docComment = \str_replace("\r\n", "\n", $docComment);
        $docComment = \preg_replace('/' . '\n' . '\s*' . '\*' . '\s?' . '/', "\n", $docComment);
        $docComment = \substr($docComment, 0, -1);

        return \rtrim($docComment, "\n");
    }

    private static function emptyHookMethodsArray(): array
    {
        return [
            'beforeClass' => ['setUpBeforeClass'],
            'before'      => ['setUp'],
            'after'       => ['tearDown'],
            'afterClass'  => ['tearDownAfterClass'],
        ];
    }

    private static function getBooleanAnnotationSetting(string $className, ?string $methodName, string $settingName): ?bool
    {
        $annotations = self::parseTestMethodAnnotations(
            $className,
            $methodName
        );

        if (isset($annotations['method'][$settingName])) {
            if ($annotations['method'][$settingName][0] === 'enabled') {
                return true;
            }

            if ($annotations['method'][$settingName][0] === 'disabled') {
                return false;
            }
        }

        if (isset($annotations['class'][$settingName])) {
            if ($annotations['class'][$settingName][0] === 'enabled') {
                return true;
            }

            if ($annotations['class'][$settingName][0] === 'disabled') {
                return false;
            }
        }

        return null;
    }

    /**
     * @throws InvalidCoversTargetException
     */
    private static function resolveElementToReflectionObjects(string $element): array
    {
        $codeToCoverList = [];

        if (\strpos($element, '\\') !== false && \function_exists($element)) {
            $codeToCoverList[] = new ReflectionFunction($element);
        } elseif (\strpos($element, '::') !== false) {
            [$className, $methodName] = \explode('::', $element);

            if (isset($methodName[0]) && $methodName[0] === '<') {
                $classes = [$className];

                foreach ($classes as $className) {
                    if (!\class_exists($className) &&
                        !\interface_exists($className) &&
                        !\trait_exists($className)) {
                        throw new InvalidCoversTargetException(
                            \sprintf(
                                'Trying to @cover or @use not existing class or ' .
                                'interface "%s".',
                                $className
                            )
                        );
                    }

                    $class      = new ReflectionClass($className);
                    $methods    = $class->getMethods();
                    $inverse    = isset($methodName[1]) && $methodName[1] === '!';
                    $visibility = 'isPublic';

                    if (\strpos($methodName, 'protected')) {
                        $visibility = 'isProtected';
                    } elseif (\strpos($methodName, 'private')) {
                        $visibility = 'isPrivate';
                    }

                    foreach ($methods as $method) {
                        if ($inverse && !$method->$visibility()) {
                            $codeToCoverList[] = $method;
                        } elseif (!$inverse && $method->$visibility()) {
                            $codeToCoverList[] = $method;
                        }
                    }
                }
            } else {
                $classes = [$className];

                foreach ($classes as $className) {
                    if ($className === '' && \function_exists($methodName)) {
                        $codeToCoverList[] = new ReflectionFunction(
                            $methodName
                        );
                    } else {
                        if (!((\class_exists($className) || \interface_exists($className) || \trait_exists($className)) &&
                            \method_exists($className, $methodName))) {
                            throw new InvalidCoversTargetException(
                                \sprintf(
                                    'Trying to @cover or @use not existing method "%s::%s".',
                                    $className,
                                    $methodName
                                )
                            );
                        }

                        $codeToCoverList[] = new ReflectionMethod(
                            $className,
                            $methodName
                        );
                    }
                }
            }
        } else {
            $extended = false;

            if (\strpos($element, '<extended>') !== false) {
                $element  = \str_replace('<extended>', '', $element);
                $extended = true;
            }

            $classes = [$element];

            if ($extended) {
                $classes = \array_merge(
                    $classes,
                    \class_implements($element),
                    \class_parents($element)
                );
            }

            foreach ($classes as $className) {
                if (!\class_exists($className) &&
                    !\interface_exists($className) &&
                    !\trait_exists($className)) {
                    throw new InvalidCoversTargetException(
                        \sprintf(
                            'Trying to @cover or @use not existing class or ' .
                            'interface "%s".',
                            $className
                        )
                    );
                }

                $codeToCoverList[] = new ReflectionClass($className);
            }
        }

        return $codeToCoverList;
    }

    private static function resolveReflectionObjectsToLines(array $reflectors): array
    {
        $result = [];

        foreach ($reflectors as $reflector) {
            if ($reflector instanceof ReflectionClass) {
                foreach ($reflector->getTraits() as $trait) {
                    $reflectors[] = $trait;
                }
            }
        }

        foreach ($reflectors as $reflector) {
            $filename = $reflector->getFileName();

            if (!isset($result[$filename])) {
                $result[$filename] = [];
            }

            $result[$filename] = \array_merge(
                $result[$filename],
                \range($reflector->getStartLine(), $reflector->getEndLine())
            );
        }

        foreach ($result as $filename => $lineNumbers) {
            $result[$filename] = \array_keys(\array_flip($lineNumbers));
        }

        return $result;
    }

    /**
     * Trims any extensions from version string that follows after
     * the <major>.<minor>[.<patch>] format
     */
    private static function sanitizeVersionNumber(string $version)
    {
        return \preg_replace(
            '/^(\d+\.\d+(?:.\d+)?).*$/',
            '$1',
            $version
        );
    }

    private static function shouldCoversAnnotationBeUsed(array $annotations): bool
    {
        if (isset($annotations['method']['coversNothing'])) {
            return false;
        }

        if (isset($annotations['method']['covers'])) {
            return true;
        }

        if (isset($annotations['class']['coversNothing'])) {
            return false;
        }

        return true;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               <?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Runner;

use PHPUnit\Framework\Test;
use PHPUnit\Util\Filesystem;

class TestResultCache implements \Serializable, TestResultCacheInterface
{
    /**
     * @var string
     */
    public const DEFAULT_RESULT_CACHE_FILENAME = '.phpunit.result.cache';

    /**
     * Provide extra protection against incomplete or corrupt caches
     *
     * @var array<string, string>
     */
    private const ALLOWED_CACHE_TEST_STATUSES = [
        BaseTestRunner::STATUS_SKIPPED,
        BaseTestRunner::STATUS_INCOMPLETE,
        BaseTestRunner::STATUS_FAILURE,
        BaseTestRunner::STATUS_ERROR,
        BaseTestRunner::STATUS_RISKY,
        BaseTestRunner::STATUS_WARNING,
    ];

    /**
     * Path and filename for result cache file
     *
     * @var string
     */
    private $cacheFilename;

    /**
     * The list of defective tests
     *
     * <code>
     * // Mark a test skipped
     * $this->defects[$testName] = BaseTestRunner::TEST_SKIPPED;
     * </code>
     *
     * @var array array<string, int>
     */
    private $defects = [];

    /**
     * The list of execution duration of suites and tests (in seconds)
     *
     * <code>
     * // Record running time for test
     * $this->times[$testName] = 1.234;
     * </code>
     *
     * @var array<string, float>
     */
    private $times = [];

    public function __construct($filename = null)
    {
        $this->cacheFilename = $filename ?? $_ENV['PHPUNIT_RESULT_CACHE'] ?? self::DEFAULT_RESULT_CACHE_FILENAME;
    }

    public function persist(): void
    {
        $this->saveToFile();
    }

    public function saveToFile(): void
    {
        if (\defined('PHPUNIT_TESTSUITE_RESULTCACHE')) {
            return;
        }

        if (!Filesystem::createDirectory(\dirname($this->cacheFilename))) {
            throw new Exception(
                \sprintf(
                    'Cannot create directory "%s" for result cache file',
                    $this->cacheFilename
                )
            );
        }

        \file_put_contents(
            $this->cacheFilename,
            \serialize($this)
        );
    }

    public function setState(string $testName, int $state): void
    {
        if ($state !== BaseTestRunner::STATUS_PASSED) {
            $this->defects[$testName] = $state;
        }
    }

    public function getState($testName): int
    {
        return $this->defects[$testName] ?? BaseTestRunner::STATUS_UNKNOWN;
    }

    public function setTime(string $testName, float $time): void
    {
        $this->times[$testName] = $time;
    }

    public function getTime($testName): float
    {
        return $this->times[$testName] ?? 0;
    }

    public function load(): void
    {
        $this->clear();

        if (\is_file($this->cacheFilename) === false) {
            return;
        }

        $cacheData = @\file_get_contents($this->cacheFilename);

        // @codeCoverageIgnoreStart
        if ($cacheData === false) {
            return;
        }
        // @codeCoverageIgnoreEnd

        $cache = @\unserialize($cacheData, ['allowed_classes' => [self::class]]);

        if ($cache === false) {
            return;
        }

        if ($cache instanceof self) {
            /* @var \PHPUnit\Runner\TestResultCache */
            $cache->copyStateToCache($this);
        }
    }

    public function copyStateToCache(self $targetCache): void
    {
        foreach ($this->defects as $name => $state) {
            $targetCache->setState($name, $state);
        }

        foreach ($this->times as $name => $time) {
            $targetCache->setTime($name, $time);
        }
    }

    public function clear(): void
    {
        $this->defects = [];
        $this->times   = [];
    }

    public function serialize(): string
    {
        return \serialize([
            'defects' => $this->defects,
            'times'   => $this->times,
        ]);
    }

    public function unserialize($serialized): void
    {
        $data = \unserialize($serialized);

        if (isset($data['times'])) {
            foreach ($data['times'] as $testName => $testTime) {
                $this->times[$testName] = (float) $testTime;
            }
        }

        if (isset($data['defects'])) {
            foreach ($data['defects'] as $testName => $testResult) {
                if (\in_array($testResult, self::ALLOWED_CACHE_TEST_STATUSES, true)) {
                    $this->defects[$testName] = $testResult;
                }
            }
        }
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Runner\PhptTestCase;

final class TextTestListRenderer
{
    public function render(TestSuite $suite): string
    {
        $buffer = 'Available test(s):' . \PHP_EOL;

        foreach (new \RecursiveIteratorIterator($suite->getIterator()) as $test) {
            if ($test instanceof TestCase) {
                $name = \sprintf(
                    '%s::%s',
                    \get_class($test),
                    \str_replace(' with data set ', '', $test->getName())
                );
            } elseif ($test instanceof PhptTestCase) {
                $name = $test->getName();
            } else {
                continue;
            }

            $buffer .= \sprintf(
                ' - %s' . \PHP_EOL,
                $name
            );
        }

        return $buffer;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             <?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util;

final class Type
{
    public static function isType(string $type): bool
    {
        switch ($type) {
            case 'numeric':
            case 'integer':
            case 'int':
            case 'iterable':
            case 'float':
            case 'string':
            case 'boolean':
            case 'bool':
            case 'null':
            case 'array':
            case 'object':
            case 'resource':
            case 'scalar':
                return true;

            default:
                return false;
        }
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         