<?php
/*
 * This file is part of sebastian/comparator.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\Comparator;

use PHPUnit\Framework\TestCase;

/**
 * @covers \SebastianBergmann\Comparator\ResourceComparator<extended>
 *
 * @uses \SebastianBergmann\Comparator\Comparator
 * @uses \SebastianBergmann\Comparator\Factory
 * @uses \SebastianBergmann\Comparator\ComparisonFailure
 */
final class ResourceComparatorTest extends TestCase
{
    /**
     * @var ResourceComparator
     */
    private $comparator;

    protected function setUp(): void
    {
        $this->comparator = new ResourceComparator;
    }

    public function acceptsSucceedsProvider()
    {
        $tmpfile1 = \tmpfile();
        $tmpfile2 = \tmpfile();

        return [
            [$tmpfile1, $tmpfile1],
            [$tmpfile2, $tmpfile2],
            [$tmpfile1, $tmpfile2]
        ];
    }

    public function acceptsFailsProvider()
    {
        $tmpfile1 = \tmpfile();

        return [
            [$tmpfile1, null],
            [null, $tmpfile1],
            [null, null]
        ];
    }

    public function assertEqualsSucceedsProvider()
    {
        $tmpfile1 = \tmpfile();
        $tmpfile2 = \tmpfile();

        return [
            [$tmpfile1, $tmpfile1],
            [$tmpfile2, $tmpfile2]
        ];
    }

    public function assertEqualsFailsProvider()
    {
        $tmpfile1 = \tmpfile();
        $tmpfile2 = \tmpfile();

        return [
            [$tmpfile1, $tmpfile2],
            [$tmpfile2, $tmpfile1]
        ];
    }

    /**
     * @dataProvider acceptsSucceedsProvider
     */
    public function testAcceptsSucceeds($expected, $actual): void
    {
        $this->assertTrue(
          $this->comparator->accepts($expected, $actual)
        );
    }

    /**
     * 