<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Routing\Tests\Matcher;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class TraceableUrlMatcherTest extends TestCase
{
    public function test()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/foo', [], [], [], '', [], ['POST']));
        $coll->add('bar', new Route('/bar/{id}', [], ['id' => '\d+']));
        $coll->add('bar1', new Route('/bar/{name}', [], ['id' => '\w+'], [], '', [], ['POST']));
        $coll->add('bar2', new Route('/foo', [], [], [], 'baz'));
        $coll->add('bar3', new Route('/foo1', [], [], [], 'baz'));
        $coll->add('bar4', new Route('/foo2', [], [], [], 'baz', [], [], 'context.getMethod() == "GET"'));

        $context = new RequestContext();
        $context->setHost('baz');

        $matcher = new TraceableUrlMatcher($coll, $context);
        $traces = $matcher->getTraces('/babar');
        $this->assertSame([0, 0, 0, 0, 0, 0], $this->getLevels($traces));

        $traces = $matcher->getTraces('/foo');
        $this->assertSame([1, 0, 0, 2], $this->getLevels($traces));

        $traces = $matcher->getTraces('/bar/12');
        $this->assertSame([0, 2], $t