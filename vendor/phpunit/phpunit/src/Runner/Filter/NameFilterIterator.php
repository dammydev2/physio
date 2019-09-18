<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util;

use PharIo\Version\VersionConstraintParser;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\CodeCoverageException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\InvalidCoversTargetException;
use PHPUnit\Framework\SelfDescribing;
use PHPUnit\Framework\SkippedTestError;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Warning;
use PHPUnit\Runner\Version;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use SebastianBergmann\Environment\OperatingSystem;
use Traversable;

final class Test
{
    /**
     * @var int
     */
    public const UNKNOWN = -1;

    /**
     * @var int
     */
    public const SMALL = 0;

    /**
     * @var int
     */
    public const MEDIUM = 1;

    /**
     * @var int
     */
    public const LARGE = 2;

    /**
     * @var string
     *
     * @todo This constant should be private (it's public because of TestTest::testGetProvidedDataRegEx)
     */
    public const REGEX_DATA_PROVIDER = '/@dataProvider\s+([a-zA-Z0-9._:-\\\\x7f-\xff]+)/';

    /**
     * @var string
     */
    private const REGEX_TEST_WITH = '/@testWith\s+/';

    /**
     * @var string
     */
    private const REGEX_EXPECTED_EXCEPTION = '(@expectedException\s+([:.\w\\\\x7f-\xff]+)(?:[\t ]+(\S*))?(?:[\t ]+(\S*))?\s*$)m';

    /**
     * @var string
     */
    private const REGEX_REQUIRES_VERSION = '/@requires\s+(?P<name>PHP(?:Unit)?)\s+(?P<operator>[<>=!]{0,2})\s*(?P<version>[\d\.-]+(dev|(RC|alpha|beta)[\d\.])?)[ \t]*\r?$/m';

    /**
     * @var string
     */
    private const REGEX_REQUIRES_VERSION_CONSTRAINT = '/@requires\s+(?P<name>PHP(?:Unit)?)\s+(?P<constraint>[\d\t -.|~^]+)[ \t]*\r?$/m';

    /**
     * @var string
     */
    private const REGEX_REQUIRES_OS = '/@requires\s+(?P<name>OS(?:FAMILY)?)\s+(?P<value>.+?)[ \t]*\r?$/m';

    /**
     * @var string
     */
    private const REGEX_REQUIRES_SETTING = '/@requires\s+(?P<name>setting)\s+(?P<setting>([^ ]+?))\s*(?P<value>[\w\.-]+[\w\.]?)?[ \t]*\r?$/m';

    /**
     * @var string
     */
    private const REGEX_REQUIRES = '/@requires\s+(?P<name>function|extension)\s+(?P<value>([^\s<>=!]+))\s*(?P<operator>[<>=!]{0,2})\s*(?P<version>[\d\.-]+[\d\.]?)?[ \t]*\r?$/m';

    /**
     * @var array
     */
    private static $annotationCache = [];

    /**
     * @var array
     */
    private static $hookMethods = [];

    public static function describe(\PHPUnit\Framework\Test $test): array
    {
        if ($test instanceof TestCase) {
            return [\get_class($test), $test->getName()];
        }

        if ($test instanceof SelfDescribing) {
            return ['', $test->toString()];
        }

        return ['', \get_class($test)];
    }

    public static function describeAsString(\PHPUnit\Framework\Test $test): string
    {
        if ($test instanceof SelfDescribing) {
            return $test->toString();
        }

        return \get_class($test);
    }

    /**
     * @throws CodeCoverageException
     *
     * @return array|bool
    