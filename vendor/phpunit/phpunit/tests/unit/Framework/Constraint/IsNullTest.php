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

use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\TestSuiteSorter;
use PHPUnit\TextUI\ResultPrinter;

class ConfigurationTest extends TestCase
{
    /**
     * @var Configuration
     */
    protected $configuration;

    protected function setUp(): void
    {
        $this->configuration = Configuration::getInstance(
            TEST_FILES_PATH . 'configuration.xml'
        );
    }

    public function testExceptionIsThrownForNotExistingConfigurationFile(): void
    {
        $this->expectException(Exception::class);

        Configuration::getInstance('not_existing_file.xml');
    }

    public function testShouldReadColorsWhenTrueInConfigurationFile(): void
    {
        $configurationFilename = TEST_FILES_PATH . 'configuration.colors.true.xml';
        $configurationInstance = Configuration::getInstance($configurationFilename);
        $configurationValues   = $configurationInstance->getPHPUnitConfiguration();

        $this->assertEquals(ResultPrinter::COLOR_AUTO, $configurationValues['colors']);
    }

    public function testShouldReadColorsWhenFalseInConfigurationFile(): void
    {
        $configurationFilename = TEST_FILES_PATH . 'configuration.colors.false.xml';
        $configurationInstance = Configuration::getInstance(