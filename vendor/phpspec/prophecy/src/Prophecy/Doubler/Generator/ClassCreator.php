<?php
/*
 * This file is part of the php-code-coverage package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\CodeCoverage;

use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\PhptTestCase;
use PHPUnit\Util\Test;
use SebastianBergmann\CodeCoverage\Driver\Driver;
use SebastianBergmann\CodeCoverage\Driver\PHPDBG;
use SebastianBergmann\CodeCoverage\Driver\Xdebug;
use SebastianBergmann\CodeCoverage\Node\Builder;
use SebastianBergmann\CodeCoverage\Node\Directory;
use SebastianBergmann\CodeUnitReverseLookup\Wizard;
use SebastianBergmann\Environment\Runtime;

/**
 * Provides collection functionality for PHP code coverage information.
 */
final class CodeCoverage
{
    /**
     * @var Driver
     */
    private $driver;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var Wizard
     */
    private $wizard;

    /**
     * @var bool
     */
    private $cacheTokens = false;

    /**
     * @var bool
     */
    private $checkForUnintentionallyCoveredCode = false;

    /**
     * @var bool
     */
    private $forceCoversAnnotation = false;

    /**
     * @var bool
     */
    private $checkForUnexecutedCoveredCode = false;

    /**
     * @var bool
     */
    private $checkForMissingCoversAnnotation = false;

    /**
     * @var bool
     */
    private $addUncoveredFilesFromWhitelist = true;

    /**
     * @var bool
     */
    private $processUncoveredFilesFromWhitelist = false;

    /**
     * @var bool
     */
    private $ignoreDeprecatedCode = false;

    /**
     * @var PhptTestCase|string|TestCase
     */
    private $currentI