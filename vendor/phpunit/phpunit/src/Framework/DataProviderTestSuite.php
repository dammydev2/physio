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

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;

class Attribute extends Composite
{
    /**
     * @var string
     */
    private $attributeName;

    public function __construct(Constraint $constraint, string $attributeName)
    {
        parent::__construct($constraint);

        $this->attributeName = $attributeName;
    }

    /**
     * Evaluates the constraint for parameter $other
     *
     * If $returnResult is set to false (the default), an exception is thrown
     * in case of a failure. null is returned otherwise.
     *
     * If $r