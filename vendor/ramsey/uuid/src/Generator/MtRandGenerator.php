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
 * @covers \SebastianBergmann\Comparator\DoubleComparator<extended>
 *
 * @uses \SebastianBergmann\Comparator\Comparator
 * @uses \SebastianBergmann\Comparator\Factory
 * @uses \SebastianBergmann\Comparator\ComparisonFailure
 */
final class DoubleComparatorTest extends TestCase
{
    /**
     * @var DoubleComparator
     */
    private $comparator;

    protected function setUp(): void
    {
        $this->comparator = new DoubleComparator;
    }

    public function acceptsSucceedsProvider()
    {
        return [
            [0, 5.0],
            [5.0, 0],
            ['5', 4.5],
            [1.2e3, 7E-10],
            [3, \acos(8)],
            [\acos(8), 3],
            [\acos(8), \acos(8)]
        ];
    }

    public function acceptsFailsProvider()
    {
        return [
            [5, 5],
            ['4.5', 5],
            [0x53