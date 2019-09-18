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

use Countable;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\SelfDescribing;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Exporter\Exporter;

/**
 * Abstract base class for constraints which can be applied to any value.
 */
abstract class Constraint implements Countable, SelfDescribing
{
    protected $exporter;

    public function __construct()
    {
        $this->exporter = new Exporter;
    }

    /**
     * Evaluates the constraint for parameter $other
     *
     * If $returnResult is set to false (the default), an exception is thrown
     * in case of a failure. null is returned otherwise.
     *
     * If $returnResult is true, the result of the evaluation is returned as
     * a boolean value instead: true in case of success, false in case of a
     * failure.
     *
     * @param mixed  $other        value or object to evaluate
     * @param string $description  Additional information about the test
     * @param bool   $returnResult Whether to return a result or throw an exception
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentExcepti