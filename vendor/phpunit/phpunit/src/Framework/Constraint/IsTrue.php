<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework\MockObject\Matcher;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\Invocation as BaseInvocation;

/**
 * Invocation matcher which looks for sets of specific parameters in the invocations.
 *
 * Checks the parameters of the incoming invocations, the parameter list is
 * checked against the defined constraints in $parameters. If the constraint
 * is met it will return true in matches().
 *
 * It takes