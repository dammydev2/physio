           ],
            ],

            [
                'Route with only optional variables',
                ['/{foo}/{bar}', ['foo' => 'foo', 'bar' => 'bar']],
                '', '#^/(?P<foo>[^/]++)?(?:/(?P<bar>[^/]++))?$#sD', ['foo', 'bar'], [
                    ['variable', '/', '[^/]++', 'bar'],
                    ['variable', '/', '[^/]++', 'foo'],
                ],
            ],

            [
                'Route with a variable in last position',
                ['/foo-{bar}'],
                '/foo-', '#^/foo\-(?P<bar>[^/]++)$#sD', ['bar'], [
                    ['variable', '-', '[^/]++', 'bar'],
                    ['text', '/foo'],
                ],
            ],

            [
                'Route with nested placeholders',
                ['/{static{var}static}'],
                '/{static', '#^/\{static(?P<var>[^/]+)static\}$#sD', ['var'], [
                    ['text', 'static}'],
                    ['variable', '', '[^/]+', 'var'],
                    ['text', '/{static'],
                ],
            ],

            [
                'Route without separator between variables',
                ['/{w}{x}{y}{z}.{_format}', ['z' => 'default-z', '_format' => 'html'], ['y' => '(y|Y)']],
                '', '#^/(?P<w>[^/\.]+)(?P<x>[^/\.]+)(?P<y>(?:y|Y))(?:(?P<z>[^/\.]++)(?:\.(?P<_format>[^/]++))?)?$#sD', ['w', 'x', 'y', 'z', '_format'], [
                    ['variable', '.', '[^/]++', '_format'],
                    ['variable', '', '[^/\.]++', 'z'],
                    ['variable', '', '(?:y|Y)', 'y'],
                    ['variable', '', '[^/\.]+', 'x'],
                    ['variable', '/', '[^/\.]+', 'w'],
                ],
            ],

            [
                'Route with a format',
                ['/foo/{bar}.{_format}'],
                '/foo', '#^/foo/(?P<bar>[^/\.]++)\.(?P<_format>[^/]++)$#sD', ['bar', '_format'], [
                    ['variable', '.', '[^/]++', '_format'],
                    ['variable', '/', '[^/\.]++', 'bar'],
                    ['text', '/foo'],
                ],
            ],

            [
                'Static non UTF-8 route',
                ["/fo\xE9"],
                "/fo\xE9", "#^/fo\xE9$#sD", [], [
                    ['text', "/fo\xE9"],
                ],
            ],

            [
                'Route with an explicit UTF-8 requirement',
                ['/{bar}', ['bar' => null], ['bar' => '.'], ['utf8' => true]],
                '', '#^/(?P<bar>.)?$#sDu', ['bar'], [
                    ['variable', '/', '.', 'bar', true],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideCompileImplicitUtf8Data
     * @expectedException \LogicException
     */
    public function testCompileImplicitUtf8Data($name, $arguments, $prefix, $regex, $variables, $tokens, $deprecationType)
    {
        $r = new \ReflectionClass('Symfony\\Component\\Routing\\Route');
        $route = $r->newInstanceArgs($arguments);

        $compiled = $route->compile();
        $this->assertEquals($prefix, $compiled->getStaticPrefix(), $name.' (static prefix)');
        $this->assertEquals($regex, $compiled->getRegex(), $name.' (regex)');
        $this->assertEquals($variables, $compiled->getVariables(), $name.' (variables)');
        $this->assertEquals($tokens, $compiled->getTokens(), $name.' (tokens)');
    }

    public function provideCompileImplicitUtf8Data()
    {
        return [
            [
                'Static UTF-8 route',
                ['/foé'],
                '/foé', '#^/foé$#sDu', [], [
                    ['text', '/foé'],
                ],
                'patterns',
            ],

            [
                'Route with an implicit UTF-8 requirement',
                ['/{bar}', ['bar' => null], ['bar' => 'é']],
                '', '#^/(?P<bar>é)?$#sDu', ['bar'], [
                    ['variable', '/', 'é', 'bar', true],
                ],
                'requirements',
            ],

            [
                'Route with a UTF-8 class requirement',
                ['/{bar}', ['bar' => null], ['bar' => '\pM']],
                '', '#^/(?P<bar>\pM)?$#sDu', ['bar'], [
                    ['variable', '/', '\pM', 'bar', true],
                ],
                'requirements',
            ],

            [
                'Route with a UTF-8 separator',
                ['/foo/{bar}§{_format}', [], [], ['compiler_class' => Utf8RouteCompiler::class]],
                '/foo', '#^/foo/(?P<bar>[^/§]++)§(?P<_format>[^/]++)$#sDu', ['bar', '_format'], [
                    ['variable', '§', '[^/]++', '_format', true],
                    ['variable', '/', '[^/§]++', 'bar', true],
                    ['text', '/foo'],
                ],
                'patterns',
            ],
        ];
    }

    /**
     * @expectedException \LogicException
     */
    public function testRouteWithSameVariableTwice()
    {
        $route = new Route('/{name}/{name}');

        $compiled = $route->compile();
    }

    /**
     * @expectedException \LogicException
     */
    public function testRouteCharsetMismatch()
    {
        $route = new Route("/\xE9/{bar}", [], ['bar' => '.'], ['utf8' => true]);

        $compiled = $route->compile();
    }

    /**
     * @expectedException \LogicException
     */
    public function testRequirementCharsetMismatch()
    {
        $route = new Route('/foo/{bar}', [], ['bar' => "\xE9"], ['utf8' => true]);

        $compiled = $route->compile();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRouteWithFragmentAsPathParameter()
    {
        $route = new Route('/{_fragment}');

        $compiled = $route->compile();
    }

    /**
     * @dataProvider getVariableNamesStartingWithADigit
     * @expectedException \DomainException
     */
    public function testRouteWithVariableNameStartingWithADigit($name)
    {
        $route = new Route('/{'.$name.'}');
        $route->compile();
    }

    public function getVariableNamesStartingWithADigit()
    {
        return [
           ['09'],
           ['123'],
           ['1e2'],
        ];
    }

    /**
     * @dataProvider provideCompileWithHostData
     */
    public function testCompileWithHost($name, $arguments, $prefix, $regex, $variables, $pathVariables, $tokens, $hostRegex, $hostVariables, $hostTokens)
    {
        $r = new \ReflectionClass('Symfony\\Component\\Routing\\Route');
        $route = $r->newInstanceArgs($arguments);

        $compiled = $route->compile();
        $this->assertEquals($prefix, $compiled->getStaticPrefix(), $name.' (static prefix)');
        $this->assertEquals($regex, str_replace(["\n", ' '], '', $compiled->getRegex()), $name.' (regex)');
        $this->assertEquals($variables, $compiled->getVariables(), $name.' (variables)');
        $this->assertEquals($pathVariables, $compiled->getPathVariables(), $name.' (path variables)');
        $this->assertEquals($tokens, $compiled->getTokens(), $name.' (tokens)');
        $this->assertEquals($hostRegex, str_replace(["\n", ' '], '', $compiled->getHostRegex()), $name.' (host regex)');
        $this->assertEquals($hostVariables, $compiled->getHostVariables(), $name.' (host variables)');
        $this->assertEquals($hostTokens, $compiled->getHostTokens(), $name.' (host tokens)');
    }

    public function provideCompileWithHostData()
    {
        return [
            [
                'Route with host pattern',
                ['/hello', [], [], [], 'www.example.com'],
                '/hello', '#^/hello$#sD', [], [], [
                    ['text', '/hello'],
                ],
                '#^www\.example\.com$#sDi', [], [
                    ['text', 'www.example.com'],
                ],
            ],
            [
                'Route with host pattern and some variables',
                ['/hello/{name}', [], [], [], 'www.example.{tld}'],
                '/hello', '#^/hello/(?P<name>[^/]++)$#sD', ['tld', 'name'], ['name'], [
                    ['variable', '/', '[^/]++', 'name'],
                    ['text', '/hello'],
                ],
                '#^www\.example\.(?P<tld>[^\.]++)$#sDi', ['tld'], [
                    ['variable', '.', '[^\.]++', 'tld'],
                    ['text', 'www.example'],
                ],
            ],
            [
                'Route with variable at beginning of host',
                ['/hello', [], [], [], '{locale}.example.{tld}'],
                '/hello', '#^/hello$#sD', ['locale', 'tld'], [], [
                    ['text', '/hello'],
                ],
                '#^(?P<locale>[^\.]++)\.example\.(?P<tld>[^\.]++)$#sDi', ['locale', 'tld'], [
                    ['variable', '.', '[^\.]++', 'tld'],
                    ['text', '.example'],
                    ['variable', '', '[^\.]++', 'locale'],
                ],
            ],
            [
                'Route with host variables that has a default value',
                ['/hello', ['locale' => 'a', 'tld' => 'b'], [], [], '{locale}.example.{tld}'],
                '/hello', '#^/hello$#sD', ['locale', 'tld'], [], [
                    ['text', '/hello'],
                ],
                '#^(?P<locale>[^\.]++)\.example\.(?P<tld>[^\.]++)$#sDi', ['locale', 'tld'], [
                    ['variable', '.', '[^\.]++', 'tld'],
                    ['text', '.example'],
                    ['variable', '', '[^\.]++', 'locale'],
                ],
            ],
        ];
    }

    /**
     * @expectedException \DomainException
     */
    public function testRouteWithTooLongVariableName()
    {
        $route = new Route(sprintf('/{%s}', str_repeat('a', RouteCompiler::VARIABLE_MAXIMUM_LENGTH + 1)));
        $route->compile();
    }

    /**
     * @dataProvider provideRemoveCapturingGroup
     */
    public function testRemoveCapturingGroup($regex, $requirement)
    {
        $route = new Route('/{foo}', [], ['foo' => $requirement]);

        $this->assertSame($regex, $route->compile()->getRegex());
    }

    public function provideRemoveCapturingGroup()
    {
        yield ['#^/(?P<foo>a(?:b|c)(?:d|e)f)$#sD', 'a(b|c)(d|e)f'];
        yield ['#^/(?P<foo>a\(b\)c)$#sD', 'a\(b\)c'];
        yield ['#^/(?P<foo>(?:b))$#sD', '(?:b)'];
        yield ['#^/(?P<foo>(?(b)b))$#sD', '(?(b)b)'];
        yield ['#^/(?P<foo>(*F))$#sD', '(*F)'];
        yield ['#^/(?P<foo>(?:(?:foo)))$#sD', '((foo))'];
    }
}

class Utf8RouteCompiler extends RouteCompiler
{
    const SEPARATORS = '/§';
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 <?php

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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

class RouterTest extends TestCase
{
    private $router = null;

    private $loader = null;

    protected function setUp()
    {
        $this->loader = $this->getMockBuilder('Symfony\Component\Config\Loader\LoaderInterface')->getMock();
        $this->router = new Router($this->loader, 'routing.yml');
    }

    public function testSetOptionsWithSupportedOptions()
    {
        $this->router->setOptions([
            'cache_dir' => './cache',
            'debug' => true,
            'resource_type' => 'ResourceType',
        ]);

        $this->assertSame('./cache', $this->router->getOption('cache_dir'));
        $this->assertTrue($this->router->getOption('debug'));
        $this->assertSame('ResourceType', $this->router->getOption('resource_type'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The Router does not support the following options: "option_foo", "option_bar"
     */
    public function testSetOptionsWithUnsupportedOptions()
    {
        $this->router->setOptions([
            'cache_dir' => './cache',
            'option_foo' => true,
            'option_bar' => 'baz',
            'resource_type' => 'ResourceType',
        ]);
    }

    public function testSetOptionWithSupportedOption()
    {
        $this->router->setOption('cache_dir', './cache');

        $this->assertSame('./cache', $this->router->getOption('cache_dir'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The Router does not support the "option_foo" option
     */
    public function testSetOptionWithUnsupportedOption()
    {
        $this->router->setOption('option_foo', true);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The Router does not support the "option_foo" option
     */
    public function testGetOptionWithUnsupportedOption()
    {
        $this->router->getOption('option_foo', true);
    }

    public function testThatRouteCollectionIsLoaded()
    {
        $this->router->setOption('resource_type', 'ResourceType');

        $routeCollection = new RouteCollection();

        $this->loader->expects($this->once())
            ->method('load')->with('routing.yml', 'ResourceType')
            ->will($this->returnValue($routeCollection));

        $this->assertSame($routeCollection, $this->router->getRouteCollection());
    }

    /**
     * @dataProvider provideMatcherOptionsPreventingCaching
     */
    public function testMatcherIsCreatedIfCacheIsNotConfigured($option)
    {
        $this->router->setOption($option, null);

        $this->loader->expects($this->once())
            ->method('load')->with('routing.yml', null)
            ->will($this->returnValue(new RouteCollection()));

        $this->assertInstanceOf('Symfony\\Component\\Routing\\Matcher\\UrlMatcher', $this->router->getMatcher());
    }

    public function provideMatcherOptionsPreventingCaching()
    {
        return [
            ['cache_dir'],
            ['matcher_cache_class'],
        ];
    }

    /**
     * @dataProvider provideGeneratorOptionsPreventingCaching
     */
    public function testGeneratorIsCreatedIfCacheIsNotConfigured($option)
    {
        $this->router->setOption($option, null);

        $this->loader->expects($this->once())
            ->method('load')->with('routing.yml', null)
            ->will($this->returnValue(new RouteCollection()));

        $this->assertInstanceOf('Symfony\\Component\\Routing\\Generator\\UrlGenerator', $this->router->getGenerator());
    }

    public function provideGeneratorOptionsPreventingCaching()
    {
        return [
            ['cache_dir'],
            ['generator_cache_class'],
        ];
    }

    public function testMatchRequestWithUrlMatcherInterface()
    {
        $matcher = $this->getMockBuilder('Symfony\Component\Routing\Matcher\UrlMatcherInterface')->getMock();
        $matcher->expects($this->once())->method('match');

        $p = new \ReflectionProperty($this->router, 'matcher');
        $p->setAccessible(true);
        $p->setValue($this->router, $matcher);

        $this->router->matchRequest(Request::create('/'));
    }

    public function testMatchRequestWithReque