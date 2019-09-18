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
use SplObjectStorage;
use stdClass;

/**
 * @covers \SebastianBergmann\Comparator\SplObjectStorageComparator<extended>
 *
 * @uses \SebastianBergmann\Comparator\Comparator
 * @uses \SebastianBergmann\Comparator\Factory
 * @uses \SebastianBergmann\Comparator\ComparisonFailure
 */
final class SplObjectStorageComparatorTest extends TestCase
{
    /**
     * @var SplObjectStorageComparator
     */
    private $comparator;

    protected function setUp(): void
    {
        $this->comparator = new SplObjectStorageComparator;
    }

