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
 * @covers \SebastianBergmann\Comparator\NumericComparator<extended>
 *
 * @uses \SebastianBergmann\Comparator\Comparator
 * @uses \SebastianBergmann\Comparator\Factory
 * @uses \SebastianBergmann\Comparator\ComparisonFailure
 */
final class NumericComparatorTest extends TestCase
{
    /**
     * @var NumericComparator
     */
    private $comparator;

    protected function setUp(): void
    {
        $this->comparator = new NumericComparator;
    }

    public function acceptsSucceedsProvider()
    {
        return [
            [5, 10],
            [8, '0'],
            ['10', 0],
            [0x74c3b00c, 42],
            [0755, 0777]
        ];
    }

    public function acceptsFailsProvider()
    {
        return [
            ['5', '10'],
            [8, 5.0],
            [5.0, 8],
            [10, null],
      