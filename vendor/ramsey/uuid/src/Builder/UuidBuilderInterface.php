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

use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

/**
 * Thrown when an assertion for string equality failed.
 */
class ComparisonFailure extends \RuntimeException
{
    /**
     * Expected value of the retrieval which does not match $actual.
     *
     * @var mixed
     */
    protected $expected;

    /**
     * Actually retrieved value which does not match $expected.
     *
     * @var mixed
     */
    protected $actual;

    /**
     * The string representation of the expected value
     *
     * @var string
     */
    protected $expectedAsString;

    /**
     * The string representation of the actual value
     *
     * @var string
     */
    protected $actualAsString;

    /**
     * @var bool
     */
    protected $identical;

    /**
     * Optional message which is pla