<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\TextUI;

use PharIo\Manifest\ApplicationName;
use PharIo\Manifest\Exception as ManifestException;
use PharIo\Manifest\ManifestLoader;
use PharIo\Version\Version as PharIoVersion;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Runner\PhptTestCase;
use PHPUnit\Runner\StandardTestSuiteLoader;
use PHPUnit\Runner\TestSuiteLoader;
use PHPUnit\Runner\TestSuiteSorter;
use PHPUnit\Runner\Version;
use PHPUnit\Util\Configuration;
use PHPUnit\Util\ConfigurationGenerator;
use PHPUnit\Util\FileLo