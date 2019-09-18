<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework\Constraint;

use PHPUnit\Framework\ExpectationFailedException;

class ArraySubsetTest extends ConstraintTestCase
{
    public static function evaluateDataProvider(): array
    {
        return [
            'loose array subset and array other' => [
                'expected' => true,
                'subset'   => ['bar' => 0],
                'other'    => ['foo' => '', 'bar' => '0'],
                'strict'   => false,
            ],
            'strict array subset and array other' => [
                'expected' => false,
                'subset'   => ['bar' => 0],
                'other'    => ['foo' => '', 'bar' => '0'],
                'strict'   => true,
            ],
            'loose array subset and ArrayObject other' => [
                'expected' => true,
                'subset'   => ['bar' => 0],
                'other'    => new \ArrayObject(['foo' => '', 'bar' => '0']),
                'strict'   => false,
            ],
            'strict ArrayObject subset and array other' => [
                'expected' => true,
                'subset'   => new \ArrayObject(['bar' => 0]),
                'other'    => ['foo' => '', 'bar' => 0],
                'strict'   => true,
            ],
        ];
    }

    /**
     * @param bool               $expected
     * @param array|\Traversable $subset
     * @param array|\Traversable $other
     * @param bool               $strict
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @dataProvider evaluateDataProvider
     */
    public function testEvaluate($expected, $subset, $other, $strict): void
    {
        $constraint = new ArraySubset($subset, $strict);

        $this->assertSame($expected, $constraint->evaluate($other, '', true));
    }

