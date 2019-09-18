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

use Exception;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @covers \SebastianBergmann\Comparator\ExceptionComparator<extended>
 *
 * @uses \SebastianBergmann\Comparator\Comparator
 * @uses \SebastianBergmann\Comparator\Factory
 * @uses \SebastianBergmann\Comparator\ComparisonFailure
 */
final class ExceptionComparatorTest extends TestCase
{
    /**
     * @var ExceptionComparator
     */
    private $comparator;

    protected function setUp(): void
    {
        $this->comparator = new ExceptionComparator;
        $this->comparator->setFactory(new Factory);
    }

    public function acceptsSucceedsProvider()
    {
        return [
            [new Exception, new Exception],
            [new RuntimeException, new RuntimeException],
            [new Exception, new RuntimeException]
        ];
    }

    pub