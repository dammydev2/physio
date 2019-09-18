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
 * @covers \SebastianBergmann\Comparator\ArrayComparator<extended>
 *
 * @uses \SebastianBergmann\Comparator\Comparator
 * @uses \SebastianBergmann\Comparator\Factory
 * @uses \SebastianBergmann\Comparator\ComparisonFailure
 */
final class ArrayComparatorTest extends TestCase
{
    /**
     * @var ArrayComparator
     */
    private $comparator;

    protected function setUp(): void
    {
        $this->comparator = new ArrayComparator;
        $this->comparator->setFactory(new Factory);
    }

    public function acceptsFailsProvider()
    {
        return [
            [[], null],
            [null, []],
            [null, null]
        ];
    }

    public function assertEqualsSucceedsProvider()
    {
        return [
            [
                ['a' => 1, 'b' => 2],
                ['b' => 2, 'a' => 1]
            ],
            [
                [1],
                ['1']
            ],
            [
                [3, 2, 1],
                [2, 3, 1],
                0,
                true
            ],
            [
                [2.3],
                [2.5],
                0.5
            ],
            [
              