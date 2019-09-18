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
use stdClass;

/**
 * @covers \SebastianBergmann\Comparator\MockObjectComparator<extended>
 *
 * @uses \SebastianBergmann\Comparator\Comparator
 * @uses \SebastianBergmann\Comparator\Factory
 * @uses \SebastianBergmann\Comparator\ComparisonFailure
 */
final class MockObjectComparatorTest extends TestCase
{
    /**
     * @var MockObjectComparator
     */
    private $comparator;

    protected function setUp(): void
    {
        $this->comparator = new MockObjectComparator;
        $this->comparator->setFactory(new Factory);
    }

    public function acceptsSucceedsProvider()
    {
        $testmock = $this->createMock(TestClass::class);
        $stdmock  = $this->createMock(stdClass::class);

        return [
            [$testmock, $testmock],
            [$stdmock, $stdmock],
            [$stdmock, $testmock]
        ];
    }

    public function acceptsFailsProvider()
    {
        $stdmock = $