<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework;

use PHPUnit\Framework\Constraint\Count;
use PHPUnit\Framework\Constraint\SameSize;
use PHPUnit\Framework\Constraint\TraversableContains;
use PHPUnit\Util\Filter;

class ConstraintTest extends TestCase
{
    public function testConstraintArrayNotHasKey(): void
    {
        $constraint = Assert::logicalNot(
            Assert::arrayHasKey(0)
        );

        $this->assertFalse($constraint->evaluate([0 => 1], '', true));
        $this->assertEquals('does not have the key 0', $constraint->toString());
        $this->assertCount(1, $constraint);

        try {
            $constraint->evaluate([0 => 1]);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
                <<<EOF
Failed asserting that an array does not have the key 0.

EOF
                ,
                TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    public function testConstraintArrayNotHasKey2(): void
  