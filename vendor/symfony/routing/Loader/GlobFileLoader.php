es existing requirements');
        $this->assertEquals('html', $collection->get('bar')->getRequirement('_format'), '->addPrefix() overrides existing requirements');
    }

    public function testResource()
    {
        $collection = new RouteCollection();
        $collection->addResource($foo = new FileResource(__DIR__.'/Fixtures/foo.xml'));
        $collection->addResource($bar = new FileResource(__DIR__.'/Fixtures/bar.xml'));
        $collection->addResource(new FileResource(__DIR__.'/Fixtures/foo.xml'));

        $this->assertEquals([$foo, $bar], $collection->getResources(),
            '->addResource() adds a resource and getResources() only returns unique ones by comparing the string representation');
    }

    public function testUniqueRouteWithGivenName()
    {
        $collection1 = new RouteCollection();
        $collection1->add('foo', new Route('/old'));
        $collection2 = new RouteCollection();
        $collection3 = new RouteCollection();
        $collection3->add('foo', $new = new Route('/new'));

        