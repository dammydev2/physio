<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework\MockObject\Builder;

use PHPUnit\Framework\MockObject\Matcher\AnyParameters;

/**
 * Builder interface for parameter matchers.
 */
interface ParametersMatch extends Match
{
    /**
     * Sets the parameters to match for, each parameter to this function will
     * be part of match. To perform specific matches or constraints create a
     * new PHPUnit\Framework\Constraint\Constraint and use it for the parameter.
     * If the parameter value is not a constraint it will use the
     * PHPUnit\Framework\Constraint\IsEqual for