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
 * @covers \SebastianBergmann\Comparator\ObjectComparator<extended>
 *
 * @uses \SebastianBergmann\Comparator\Comparator
 * @uses \SebastianBergmann\Comparator\Factory
 * @uses \SebastianBergmann\Comparator\ComparisonFailure
 */
final class ObjectComparatorTest extends TestCase
{
    /**
     * @var ObjectComparator
     */
    private $comparator;

    protected function setUp(): void
    {
        $this->comparator = new ObjectComparator;
        $this->comparator->setFactory(new Factory);
    }

    public function acceptsSucceedsProvider()
    {
        return [
            [new TestClass, new TestClass],
            [new stdClass, new stdClass],
            [new stdClass, new TestClass]
        ];
    }

    public function acceptsFailsProvider()
    {
        return [
            [new stdClass, null],
            [null, new stdClass],
            [null, null]
        ];
    }

    public function assertEqualsSucceedsProvider()
    {
        // cyclic dependencies
        $book1                  = new Book;
        $book1->author          = new Author('Terry Pratchett');
        $book1->author->books[] = $book1;
        $book2                  = new Book;
        $book2->author          = new Author('Terry Pratchett');
        $book2->author->books[] = $book2;

        $object1 = new SampleClass(4, 8, 15);
        $object2 = new SampleClass(4, 8, 15);

        return [
            [$object1, $object1],
            [$object1, $object2]