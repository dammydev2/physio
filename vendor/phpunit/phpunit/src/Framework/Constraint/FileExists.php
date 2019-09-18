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

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\MockObject\Matcher;
use PHPUnit\Framework\MockObject\Matcher\Invocation;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\MockObject\Stub\MatcherCollection;

/**
 * Builder for mocked or stubbed invocations.
 *
 * Provides methods for building expectations without having to resort to
 * instantiating the various matchers manually. These methods also form a
 * more natural way of reading the expectation. This class should be together
 * with the test case PHPUnit\Framework\MockObject\TestCase.
 */
class InvocationMocker implements MethodNameMatch
{
    /**
     * @var MatcherCollection
     */
    private $collection;

    /**
     * @var Matcher
     */
    private $matcher;

    /**
     * @var string[]
     */
    private $configurableMethods;

    public function __construct(MatcherCollection $collection, Invocation $invocationMatcher, array $configurableMethods)
    {
        $this->collection = $collection;
       