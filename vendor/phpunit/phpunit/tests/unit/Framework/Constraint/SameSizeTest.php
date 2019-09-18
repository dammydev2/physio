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

use PharIo\Version\VersionConstraint;
use PHPUnit\Framework\CodeCoverageException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Warning;

class TestTest extends TestCase
{
    /**
     * @todo Split up in separate tests
     */
    public function testGetExpectedException(): void
    {
        $this->assertArraySubset(
            ['class' => 'FooBarBaz', 'code' => null, 'message' => ''],
            Test::getExpectedException(\ExceptionTest::class, 'testOne')
        );

        $this->assertArraySubset(
            ['class' => 'Foo_Bar_Baz', 'code' => null, 'message' => ''],
            Test::getExpectedException(\ExceptionTest::class, 'testTwo')
        );

        $this->assertArraySubset(
            ['class' => 'Foo\Bar\Baz', 'code' => null, 'message' => ''],
            Test::getExpectedException(\ExceptionTest::class, 'testThree')
        );

        $this->assertArraySubset(
            ['class' => 'ほげ', 'code' => null, 'message' => ''],
            Test::getExpectedException(\ExceptionTest::class, 'testFour')
        );

        $this->assertArraySubset(
            ['class' => 'Class', 'code' => 1234, 'message' => 'Message'],
            Test::getExpectedException(\ExceptionTest::class, 'testFive')
        );

        $this->assertArraySubset(
            ['class' => 'Class', 'code' => 1234, 'message' => 'Message'],
            Test::getExpectedException(\ExceptionTest::class, 'testSix')
        );

        $this->assertArraySubset(
            ['class' => 'Class', 'code' => 'ExceptionCode', 'message' => 'Message'],
            Test::getExpectedException(\ExceptionTest::cl