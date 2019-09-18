  $this->assertEquals(['foo', null], self::$resolver->getArguments($request, $controller));

        // Test default bar overridden by request attribute
        $request->attributes->set('bar', 'bar');

        $this->assertEquals(['foo', 'bar'], self::$resolver->getArguments($request, $controller));
    }

    public function testGetArgumentsFromFunctionName()
    {
        $request = Request::create('/');
        $request->attributes->set('foo', 'foo');
        $request->attributes->set('foobar', 'foobar');
        $controller = __NAMESPACE__.'\controller_function';

        $this->assertEquals(['foo', 'foobar'], self::$resolver->getArguments($request, $controller));
    }

    public function testGetArgumentsFailsOnUnresolvedValue()
    {
        $request = Request::create('/');
        $request->attributes->set('foo', 'foo');
        $request->attributes->set('foobar', 'foobar');
        $controller = [new self(), 'controllerWithFooBarFoobar'];

        try {
            self::$resolver->getArguments($request, $controller);
            $this->fail('->getArguments() throws a \RuntimeException exception if it cannot determine the argument value');
        } catch (\Exception $e) {
            $this->assertInstanceOf('\RuntimeException', $e, '->getArguments() throws a \RuntimeException exception if it cannot determine the argument value');
        }
    }

    public function testGetArgumentsInjectsRequest()
    {
        $request = Request::create('/');
        $controller = [new self(), 'controllerWithRequest'];

        $this->assertEquals([$request], self::$resolver->getArguments($request, $controller), '->getArguments() injects the request');
    }

    public function testGetArgumentsInjectsExtendingRequest()
    {
        $request = ExtendingRequest::create('/');
        $controller = [new self(), 'controllerWithExtendingRequest'];

        $this->assertEquals([$request], self::$resolver->getArguments($request, $controller), '->getArguments() injects the request when extended');
    }

    public function testGetVariadicArguments()
    {
        $request = Request::create('/');
        $request->attributes->set('foo', 'foo');
        $request->attributes->set('bar', ['foo', 'bar']);
        $controller = [new VariadicController(), 'action'];

        $this->assertEquals(['foo', 'foo', 'bar'], self::$resolver->getArguments($request, $controller));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetVariadicArgumentsWithoutArrayInRequest()
    {
        $request = Request::create('/');
        $request->attributes->set('foo', 'foo');
        $request->attributes->set('bar', 'foo');
        $controller = [new VariadicController(), 'action'];

        self::$resolver->getArguments($request, $controller);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetArgumentWithoutArray()
    {
        $factory = new ArgumentMetadataFactory();
        $valueResolver = $this->getMockBuilder(ArgumentValueResolverInterface::class)->getMock();
        $resolver = new ArgumentR