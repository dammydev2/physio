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
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class UrlMatcherTest extends TestCase
{
    public function testNoMethodSoAllowed()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/foo'));

        $matcher = $this->getUrlMatcher($coll);
        $this->assertInternalType('array', $matcher->match('/foo'));
    }

    public function testMethodNotAllowed()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/foo', [], [], [], '', [], ['post']));

        $matcher = $this->getUrlMatcher($coll);

        try {
            $matcher->match('/foo');
            $this->fail();
        } catch (MethodNotAllowedException $e) {
            $this->assertEquals(['POST'], $e->getAllowedMethods());
        }
    }

    public function testMethodNotAllowedOnRoot()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/', [], [], [], '', [], ['GET']));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'POST'));

        try {
            $matcher->match('/');
            $this->fail();
        } catch (MethodNotAllowedException $e) {
            $this->assertEquals(['GET'], $e->getAllowedMethods());
        }
    }

    public function testHeadAllowedWhenRequirementContainsGet()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/foo', [], [], [], '', [], ['get']));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'head'));
        $this->assertInternalType('array', $matcher->match('/foo'));
    }

    public function testMethodNotAllowedAggregatesAllowedMethods()
    {
        $coll = new RouteCollection();
        $coll->add('foo1', new Route('/foo', [], [], [], '', [], ['post']));
        $coll->add('foo2', new Route('/foo', [], [], [], '', [], ['put', 'delete']));

        $matcher = $this->getUrlMatcher($coll);

        try {
            $matcher->match('/foo');
            $this->fail();
        } catch (MethodNotAllowedException $e) {
            $this->assertEquals(['POST', 'PUT', 'DELETE'], $e->getAllowedMethods());
        }
    }

    public function testPatternMatchAndParameterReturn()
    {
        $collection = new RouteCollection();
        $collection->add('foo', new Route('/foo/{bar}'));
        $matcher = $this->getUrlMatcher($collection);
        try {
            $matcher->match('/no-match');
            $this->fail();
        } catch (ResourceNotFoundException $e) {
        }

        $this->assertEquals(['_route' => 'foo', 'bar' => 'baz'], $matcher->match('/foo/baz'));
    }

    public function testDefaultsAreMerged()
    {
        // test that defaults are merged
        $collection = new RouteCollection();
        $collection->add('foo', new Route('/foo/{bar}', ['def' => 'test']));
        $matcher = $this->getUrlMatcher($collection);
        $this->assertEquals(['_route' => 'foo', 'bar' => 'baz', 'def' => 'test'], $matcher->match('/foo/baz'));
    }

    public function testMethodIsIgnoredIfNoMethodGiven()
    {
        // test that route "method" is ignored if no method is given in the context
        $collection = new RouteCollection();
        $collection->add('foo', new Route('/foo', [], [], [], '', [], ['get', 'head']));
        $matcher = $this->getUrlMatcher($collection);
        $this->assertInternalType('array', $matcher->match('/foo'));

        // route does not match with POST method context
        $matcher = $this->getUrlMatcher($collection, new RequestContext('', 'post'));
        try {
            $matcher->match('/foo');
            $this->fail();
        } catch (MethodNotAllowedException $e) {
        }

        // route does match with GET or HEAD method context
        $matcher = $this->getUrlMatcher($collection);
        $this->assertInternalType('array', $matcher->match('/foo'));
        $matcher = $this->getUrlMatcher($collection, new RequestContext('', 'head'));
        $this->assertInternalType('array', $matcher->match('/foo'));
    }

    public function testRouteWithOptionalVariableAsFirstSegment()
    {
        $collection = new RouteCollection();
        $collection->add('bar', new Route('/{bar}/foo', ['bar' => 'bar'], ['bar' => 'foo|bar']));
        $matcher = $this->getUrlMatcher($collection);
        $this->assertEquals(['_route' => 'bar', 'bar' => 'bar'], $matcher->match('/bar/foo'));
        $this->assertEquals(['_route' => 'bar', 'bar' => 'foo'], $matcher->match('/foo/foo'));

        $collection = new RouteCollection();
        $collection->add('bar', new Route('/{bar}', ['bar' => 'bar'], ['bar' => 'foo|bar']));
        $matcher = $this->getUrlMatcher($collection);
        $this->assertEquals(['_route' => 'bar', 'bar' => 'foo'], $matcher->match('/foo'));
        $this->assertEquals(['_route' => 'bar', 'bar' => 'bar'], $matcher->match('/'));
    }

    public function testRouteWithOnlyOptionalVariables()
    {
        $collection = new RouteCollection();
        $collection->add('bar', new Route('/{foo}/{bar}', ['foo' => 'foo', 'bar' => 'bar'], []));
        $matcher = $this->getUrlMatcher($collection);
        $this->assertEquals(['_route' => 'bar', 'foo' => 'foo', 'bar' => 'bar'], $matcher->match('/'));
        $this->assertEquals(['_route' => 'bar', 'foo' => 'a', 'bar' => 'bar'], $matcher->match('/a'));
        $this->assertEquals(['_route' => 'bar', 'foo' => 'a', 'bar' => 'b'], $matcher->match('/a/b'));
    }

    public function testMatchWithPrefixes()
    {
        $collection = new RouteCollection();
        $collection->add('foo', new Route('/{foo}'));
        $collection->addPrefix('/b');
        $collection->addPrefix('/a');

        $matcher = $this->getUrlMatcher($collection);
        $this->assertEquals(['_route' => 'foo', 'foo' => 'foo'], $matcher->match('/a/b/foo'));
    }

    public function testMatchWithDynamicPrefix()
    {
     