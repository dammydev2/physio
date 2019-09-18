<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Routing\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCompiler;

class RouteCompilerTest extends TestCase
{
    /**
     * @dataProvider provideCompileData
     */
    public function testCompile($name, $arguments, $prefix, $regex, $variables, $tokens)
    {
        $r = new \ReflectionClass('Symfony\\Component\\Routing\\Route');
        $route = $r->newInstanceArgs($arguments);

        $compiled = $route->compile();
        $this->assertEquals($prefix, $compiled->getStaticPrefix(), $name.' (static prefix)');
        $this->assertEquals($regex, $compiled->getRegex(), $name.' (regex)');
        $this->assertEquals($variables, $compiled->getVariables(), $name.' (variables)');
        $this->assertEquals($tokens, $compiled->getTokens(), $name.' (tokens)');
    }

    public function provideCompileData()
    {
        return [
            [
                'Static route',
                ['/foo'],
                '/foo', '#^/foo$#sD', [], [
                    ['text', '/foo'],
                ],
            ],

            [
                'Route with a variable',
                ['/foo/{bar}'],
                '/foo', '#^/foo/(?P<bar>[^/]++)$#sD', ['bar'], [
                    ['variable', '/', '[^/]++', 'bar'],
                    ['text', '/foo'],
                ],
            ],

            [
                'Route with a variable that has a default value',
                ['/foo/{bar}', ['bar' => 'bar']],
                '/foo', '#^/foo(?:/(?P<bar>[^/]++))?$#sD', ['bar'], [
                    ['variable', '/', '[^/]++', 'bar'],
                    ['text', '/foo'],
                ],
            ],

            [
                'Route wi