<?php
/*
 * This file is part of PharIo\Version.
 *
 * (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PharIo\Version;

use PHPUnit\Framework\TestCase;

/**
 * @covers \PharIo\Version\Version
 */
class VersionTest extends TestCase {
    /**
     * @dataProvider versionProvider
     *
     * @param string $versionString
     * @param string $expectedMajor
     * @param string $expectedMinor
     * @param string $expectedPatch
     * @param string $expectedPreReleaseValue
     * @param int $expectedReleaseCount
     */
    public function testParsesVersionNumbers(
        $versionString,
        $expectedMajor,
        $expectedMinor,
        $expectedPatch,
        $expectedPreReleaseValue = '',
        $expectedReleaseCount = 0
    ) {
        $version = new Version($versionString);

        $this->assertSame($expectedMajor, $version->getMajor()->getValue());
        $this->assertSame($expectedMinor, $version->getMinor()->getValue());
        $this->assertSame($expectedPatch, $version->getPatch()->getValue());
        if ($expectedPreReleaseValue !== '') {
            $this->assertSame($expectedPreReleaseValue, $version->getPreReleaseSuffix()->getValue());
        }
        if ($expectedReleaseCount !== 0) {
            $this->assertSame($expectedReleaseCount, $version->getPreReleaseSuffix()->getNumber());
        }

        $this->assertSame($versionString, $version->getVersionString());
    }

    public function versionProvider() {
        return [
            ['0.0.1', '0', '0', '1'],
            ['0.1.2', '0',