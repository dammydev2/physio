<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework\MockObject;

use Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\Builder\InvocationMocker as BuilderInvocationMocker;
use PHPUnit\Framework\MockObject\Builder\Match;
use PHPUnit\Framework\MockObject\Builder\NamespaceMatch;
use PHPUnit\Framework\MockObject\Matcher\DeferredError;
use PHPUnit\Framework\MockObject\Matcher\Invocation as MatcherInvocation;
use PHPUnit\Framework\MockObject\Stub\MatcherCollection;

/**
 * Mocker for invocations which are sent from
 * MockObject objects.
 *
 * Keeps track of all expectations and stubs as well as registering
 * identifications for builders.
 */
class InvocationMocker implements MatcherCollection, Invokable, NamespaceMatch
{
    /**
     * @var MatcherInvocation[]
     */
    private $matchers = [];

    /**
     * @var Match[]
     */
    private $builderMap = [];

    /**
     * @var string[]
     */
    private $configurableMethods;

    /**
     * @var bool
     */
    private $returnValueGeneration;

    public function __construct(array $configurableMethods, bool $returnValueGeneration)
    {
        $this->configurableMethods   = $configurableMethods;
        $this->returnValueGeneration = $returnValueGeneration;
    }

    public function addMatcher(MatcherInvocation $matcher): void
    {
        $this->matchers[] = $matcher;
    }

    public function hasMatchers()
    {
        foreach ($this->matchers as $matcher) {
            if ($matcher->hasMatchers()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return null|bool
     */
    public function lookupId($id)
    {
        if (isset($this->builderMap[$id])) {
            return $this->builderMap[$id];
        }
    }

    /**
     * @throws RuntimeExcept