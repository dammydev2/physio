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
 * @covers \SebastianBergmann\Comparator\ScalarComparator<extended>
 *
 * @uses \SebastianBergmann\Comparator\Comparator
 * @uses \SebastianBergmann\Comparator\Factory
 * @uses \SebastianBergmann\Comparator\ComparisonFailure
 */
final class ScalarComparatorTest extends TestCase
{
    /**
     * @var ScalarComparator
     */
    private $comparator;

    protected function setUp(): void
    {
        $this->comparator = new ScalarComparator;
    }

    public function acceptsSucceedsProvider()
    {
        return [
            ['string', 'string'],
            [new ClassWithToString, 'string'],
            ['string', new ClassWithToString],
            ['string', null],
            [false, 'string'],
            [false, true],
            [null, false],
            [null, null],
            ['10', 10],
            ['', false],
            ['1', true],
            [1, true],
            [0, false],
            [0.1, '0.1']
        ];
    }

    public function acceptsFailsProvider()
    {
        return [
            [[], []],
            ['string', []],
            [new ClassWithToString, new ClassWithToString],
            [false, new ClassWithToString],
            [\tmpfile(), \tmpfile()]
        ];
    }

    pub