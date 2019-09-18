    }

    /**
     * @expectedException \Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function testSchemeRequirement()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/foo', [], [], [], '', ['https']));
        $matcher = $this->getUrlMatcher($coll);
        $matcher->match('/foo');
    }

    /**
     * @expectedException \Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function testSchemeRequirementForNonSafeMethod()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/foo', [], [], [], '', ['https']));

        $context = new RequestContext();
        $context->setMethod('POST');
        $matcher = $this->getUrlMatcher($coll, $context);
        $matcher->match('/foo');
    }

    public function testSamePathWithDifferentScheme()
    {
        $coll = new RouteCollection();
        $coll->add('https_route', new Route('/', [], [], [], '', ['https']));
        $coll->add('http_route', new Route('/', [], [], [], '', ['http']));
        $matcher = $this->getUrlMatcher($coll);
        $this->assertEquals(['_route' => 'http_route'], $matcher->match('/'));
    }

    /**
     * @expectedException \Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function testCondition()
    {
        $coll = new RouteCollection();
        $route = new Route('/foo');
        $route->setCondition('context.getMethod() == "POST"');
        $coll->add('foo', $route);
        $matcher = $this->getUrlMatcher($coll);
        $matcher->match('/foo');
    }

    public function testRequestCondition()
    {
        $coll = new RouteCollection();
        $route = new Route('/foo/{bar}');
        $route->setCondition('request.getBaseUrl() == "/bar"');
        $coll->add('bar', $route);
        $route = new Route('/foo/{bar}');
        $route->setCondition('request.getBaseUrl() == "/sub/front.php" and request.getPathInfo() == "/foo/bar"');
        $coll->add('foo', $