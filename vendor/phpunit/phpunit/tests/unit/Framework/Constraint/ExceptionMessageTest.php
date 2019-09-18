<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Runner;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestSuite;

/**
 * @group test-reorder
 */
class TestSuiteSorterTest extends TestCase
{
    /**
     * Constants to improve clarity of @dataprovider
     */
    private const IGNORE_DEPENDENCIES  = false;

    private const RESOLVE_DEPENDENCIES = true;

    private const MULTIDEPENDENCYTEST_EXECUTION_ORDER = [
        \MultiDependencyTest::class . '::testOne',
        \MultiDependencyTest::class . '::testTwo',
        \MultiDependencyTest::class . '::testThree',
        \MultiDependencyTest::class . '::testFour',
        \MultiDependencyTest::class . '::testFive',
    ];

    public function testThrowsExceptionWhenUsingInvalidOrderOption(): void
    {
        $suite = new TestSuite;
        $suite->addTestSuite(\MultiDependencyTest::class);
        $sorter = new TestSuiteSorter;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('$order must be one of TestSuiteSorter::ORDER_DEFAULT, TestSuiteSorter::ORDER_REVERSED, or TestSuiteSorter::O