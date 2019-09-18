);
        $this->assertTrue($route->hasScheme('htTp'));
        $this->assertFalse($route->hasScheme('httpS'));
        $route->setSchemes(['HttpS', 'hTTp']);
        $this->assertEquals(['https', 'http'], $route->getSchemes(), '->setSchemes() accepts an array of schemes and lowercases them');
        $this->assertTrue($route->hasScheme('htTp'));
        $this->assertTrue($route->hasScheme('httpS'));
    }

    public function testMethod()
    {
        $route = new Route('/');
        $this->assertEquals([], $route->getMethods(), 'methods is initialized with []');
        $route->setMethods('gEt');
        $this->assertEquals(['GET'], $route->getMethods(), '->setMethods() accepts a single method string and uppercases it');
        $route->setMethods(['gEt', 'PosT']);
        $this->assertEquals(['GET', 'POST'], $route->getMethods(), '->setMethods() accepts an array of methods and uppercases them');
    }

    public function testCondition()
    {
        $route = new Route('/');
        $this->assertSame('', $route->getCondition());
        $route->setCondition('context.getMethod() == "GET"');
        $this->assertSame('context.getMethod() == "GET"', $route->getCondition());
    }

    public function testCompile()
    {
        $route = new Route('/{foo}');
        $this->assertInstanceOf('Symfony\Component\Routing\CompiledRoute', $compiled = $route->compile(), '->compile() returns a compiled route');
        $this->assertSame($compiled, $route->compile(), '->compile() only compiled the route once if unchanged');
        $route->setRequirement('foo', '.*');
        $this->assertNotSame($compiled, $route->compile(), '->compile() recompiles if the route was modified');
    }

    public function testSerialize()
    {
        $route = new Route('/prefix/{foo}', ['foo' => 'default'], ['foo' => '\d+']);

        $serialized = serialize($route);
        $unserialized = unserialize($serialized);

        $this->assertEquals($route, $unserialized);
        $this->assertNotSame($route, $unserialized);
    }

    public function testInlineDefaultAndRequirement()
    {
        $this->assertEquals((new Route('/foo/{bar}'))->setDefault('bar', null), new Route('/foo/{bar?}'));
        $this->assertEquals((new Route('/foo/{bar}'))->setDefault('bar', 'baz'), new Route('/foo/{bar?baz}'));
        $this->assertEquals((new Route('/foo/{bar}'))->setDefault('bar', 'baz<buz>'), new Route('/foo/{bar?baz<buz>}'));
        $this->assertEquals((new Route('/foo/{bar}'))->setDefault('bar', 'baz'), new Route('/foo/{bar?}', ['bar' => 'baz']));

        $this->assertEquals((new Route('/foo/{bar}'))->setRequirement('bar', '.*'), new Route('/foo/{bar<.*>}'));
        $this->assertEquals((new Route('/foo/{bar}'))->setRequirement('bar', '>'), new Route('/foo/{bar<>>}'));
        $this->assertEquals((new Route('/foo/{bar}'))->setRequirem