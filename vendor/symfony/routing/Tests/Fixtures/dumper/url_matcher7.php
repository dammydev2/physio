 '', [], ['POST']));
        $coll->add('bar', new Route('/{foo}', [], [], [], '', [], ['GET']));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'POST'));
        $this->assertEquals(['foo' => 'bar', '_route' => 'foo'], $matcher->match('/bar/'));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'GET'));
        $this->assertEquals(['foo' => 'bar', '_route' => 'bar'], $matcher->match('/bar'));
    }

    public function testVariableVariationInTrailingSlashWithMethodsInReverse()
    {
        // The order should not matter
        $coll = new RouteCollection();
        $coll->add('bar', new Route('/{foo}', [], [], [], '', [], ['GET']));
        $coll->add('foo', new Route('/{foo}/', [], [], [], '', [], ['POST']));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'POST'));
        $this->assertEquals(['foo' => 'bar', '_route' => 'foo'], $matcher->match('/bar/'));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'GET'));
        $this->assertEquals(['foo' => 'bar', '_route' => 'bar'], $matcher->match('/bar'));
    }

    public function testMixOfStaticAndVariableVariationInTrailingSlashWithHosts()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/foo/', [], [], [], 'foo.example.com'));
        $coll->add('bar', new Route('/{foo}', [], [], [], 'bar.example.com'));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'GET', 'foo.example.com'));
        $this->assertEquals(['_route' => 'foo'], $matcher->match('/foo/'));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'GET', 'bar.example.com'));
        $this->assertEquals(['foo' => 'bar', '_route' => 'bar'], $matcher->match('/bar'));
    }

    public function testMixOfStaticAndVariableVariationInTrailingSlashWithMethods()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/foo/', [], [], [], '', [], ['POST']));
        $coll->add('bar', new Route('/{foo}', [], [], [], '', [], ['GET']));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'POST'));
        $this->assertEquals(['_route' => 'foo'], $matcher->match('/foo/'));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'GET'));
        $this->assertEquals(['foo' => 'bar', '_route' => 'bar'], $matcher->match('/bar'));
        $this->assertEquals(['foo' => 'foo', '_route' => 'bar'], $matcher->match('/foo'));
    }

    /**
     * @expectedException \Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function testWithOutHostHostDoesNotMatch()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/foo/{foo}', [], [], [], '{locale}.example.com'));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'GET', 'example.com'));
        $matcher->match('/foo/bar');
    }

    /**
     * @expectedException \Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function testPathIsCaseSensitive()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/locale', [], ['locale' => 'EN|FR|DE']));

        $matcher = $this->getUrlMatcher($coll);
        $matcher->match('/en');
    }

    public function testHostIsCaseInsensitive()
    {
        $coll = new RouteCollection();
