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
use PHPUnit\Framework\TestFailure;

class TraversableContainsTest extends ConstraintTestCase
{
    public function testConstraintTraversableCheckForObjectIdentityForDefaultCase(): void
    {
        $constraint = new TraversableContains('foo');

        $this->assertTrue($constraint->evaluate([0], '', true));
        $this->assertTrue($constraint->evaluate([true], '', true));
    }

    public function testConstraintTraversableCheckForObjectIdentityForPrimitiveType(): void
    {
        $constraint = new TraversableContains('foo', true, true);

        $this->assertFalse($constraint->evaluate([0], '', true));
        $this->assertFalse($constraint->evaluate([true], '', true));
    }

    public function testConstraintTraversableWithRightValue(): void
    {
        $constraint = new TraversableContains('foo');

        $this->assertTrue($constraint->evaluate(['foo'], '', true));
    }

    public function testConstraintTraversableWithFailValue(): void
    {
        $constraint = new TraversableContains('foo');

        $this->assertFalse($constraint->evaluate(['bar'], '', true));
    }

    public function testConstraintTraversableCountMethods(): void
    {
        $constraint = new TraversableContains('foo');

        $this->assertCount(1, $constraint);
    }

    public functi