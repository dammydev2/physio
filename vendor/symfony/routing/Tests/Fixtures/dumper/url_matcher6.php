> 'bar', 'locale' => 'en'], $matcher->match('/bar/bar'));
    }

    public function testVariationInTrailingSlashWithHosts()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/foo/', [], [], [], 'foo.example.com'));
        $coll->add('bar', new Route('/foo', [], [], [], 'bar.example.com'));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'GET', 'foo.example.com'));
        $this->assertEquals(['_route' => 'foo'], $matcher->match('/foo/'));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'GET', 'bar.example.com'));
        $this->assertEquals(['_route' => 'bar'], $matcher->match('/foo'));
    }

    public function testVariationInTrailingSlashWithHostsInReverse()
    {
        // The order should not matter
        $coll = new RouteCollection();
        $coll->add('bar', new Route('/foo', [], [], [], 'bar.example.com'));
        $coll->add('foo', new Route('/foo/', [], [], [], 'foo.example.com'));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'GET', 'foo.example.com'));
        $this->assertEquals(['_route' => 'foo'], $matcher->match('/foo/'));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'GET', 'bar.example.com'));
        $this->assertEquals(['_route' => 'bar'], $matcher->match('/foo'));
    }

    public function testVariationInTrailingSlashWithHostsAndVariable()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/{foo}/', [], [], [], 'foo.example.com'));
        $coll->add('bar', new Route('/{foo}', [], [], [], 'bar.example.com'));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'GET', 'foo.example.com'));
        $this->assertEquals(['foo' => 'bar', '_route' => 'foo'], $matcher->match('/bar/'));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'GET', 'bar.example.com'));
        $this->assertEquals(['foo' => 'bar', '_route' => 'bar'], $matcher->match('/bar'));
    }

    public function testVariationInTrailingSlashWithHostsAndVariableInReverse()
    {
        // The order should not matter
        $coll = new RouteCollection();
        $coll->add('bar', new Route('/{foo}', [], [], [], 'bar.example.com'));
        $coll->add('foo', new Route('/{foo}/', [], [], [], 'foo.example.com'));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'GET', 'foo.example.com'));
        $this->assertEquals(['foo' => 'bar', '_route' => 'foo'], $matcher->match('/bar/'));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'GET', 'bar.example.com'));
        $this->assertEquals(['foo' => 'bar', '_route' => 'bar'], $matcher->match('/bar'));
    }

    public function testVariationInTrailingSlashWithMethods()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/foo/', [], [], [], '', [], ['POST']));
        $coll->add('bar', new Route('/foo', [], [], [], '', [], ['GET']));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'POST'));
        $this->assertEquals(['_route' => 'foo'], $matcher->match('/foo/'));

        $matcher = $this->getUrlMatcher($coll, new RequestContext('', 'GET'));
        $this->assertEquals(['_route' => 'bar'], $matcher->match('/foo'));
    }

    public function testVariationInTrailingSlashWithMet