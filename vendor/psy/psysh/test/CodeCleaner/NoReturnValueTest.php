<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Test\VersionUpdater;

use Psy\Shell;

class GitHubCheckerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider malformedResults
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unable to check for updates
     *
     * @param mixed $input
     */
    public function testExceptionInvocation($input)
    {
        $checker = $this->getMockBuilder('Psy\\VersionUpdater\\GitHubChecker')
            ->setMethods(['fetchLatestRelease'])
            ->getMock();
        $checker->expects($t