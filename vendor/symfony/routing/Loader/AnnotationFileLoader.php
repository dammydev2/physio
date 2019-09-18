/imported/foo1'));
        $importedCollection->add('imported_route2', new Route('/imported/foo2'));

        $loader = $this->getMockBuilder('Symfony\Component\Config\Loader\LoaderInterface')->getMock();
        // make this loader able to do the import - keeps mocking simple
        $loader->expects($this->any())
            ->method('supports')
            ->will($this->returnValue(true));
        $loader
            ->expects($this->once())
            ->method('load')
            ->will($this->returnValue($importedCollection));

        $routes = new RouteCollectionBuilder($loader);

        // 1) Add a route
        $routes->add('/checkout', 'AppBundle:Order:checkout', 'checkout_route');
        // 2) Import from a file
        $routes->mount('/', $routes->import('admin_routing.yml'));
        // 3) Add another route
        $routes->add('/', 'AppBundle:Default:homepage', 'homepage');
        // 4) Add another route
        $routes->add('/admin', 'AppBundle:Admin:dashboard', 'admin_dashboard');

        // set a default value
        $routes->setDefault('_locale', 'fr');

        $actualCollection = $routes->build();

        $this->assertCount(5, $actualCollection);
        $actualRouteNames = array_keys($actualCollection->all());
        $this->assertEquals([
            'checkout_route',
            'imported_route1',
            'imported_route2',
            'homepage',
            'admin_dashboard',
        ], $actualRouteNames);

        // make sure the defaults were set
        $checkoutRoute = $actualCollection->get('checkout_route');
        $defaults = $checkoutRoute->getDefaults();
        $this->assertArrayHasKey('_locale', $defaults);
        $this->assertEquals('fr', $defaults['_locale']);
    }

    public function testFlushSetsRouteNames()
    {
        $collectionBuilder = new RouteCollectionBuilder();

        // add a "named" route
        $collectionBuilder->add('/admin', 'AppBundle:Admin:dashboard', 'admin_dashboard');
        // add an unnamed route
        $collectionBuilder->add('/blogs', 'AppBundle:Blog:list')
            ->setMethods(['GET']);

        // integer route names are allowed - they don't confuse things
        $collectionBuilder->add('/products', 'AppBundle:Product:list', 100);

        $actualCollection = $collectionBuilder->build();
        $actualRouteNames = array_keys($actualCollection->all());
        $this->assertEquals([
            'admin_dashboard',
            'GET_blogs',
            '100',
        ], $actualRouteNames);
    }

    public function testFlushSetsDetailsOnChildrenRoutes()
    {
        $routes = new RouteCollectionBuilder();

        $routes->add('/blogs/{page}', 'listAction', 'blog_list')
            // unique things for the route
            ->setDefault('page', 1)
            ->setRequirement('id', '\d+')
            ->setOption('expose', true)
            // things that the collection will try to override (but won't)
            ->setDefault('_format', 'html')
            ->setRequirement('_format', 'json|xml')
            ->setOption('fooBar', true)
            ->setHost('example.com')
            ->setCondition('request.isSecure()')
            ->setSchemes(['https'])
            ->setMethods(['POST']);

        // a simple route, nothing added to it
        $routes->add('/blogs/{id}', 'editAction', 'blog_edit');

        // configure the collection itself
        $routes
            // things that will not override the child route
            ->setDefault('_format', 'json')
            ->setRequirement('_format', 'xml')
            ->setOption('fooBar', false)
            ->setHost('symfony.com')
            ->setCondition('request.query.get("page")==1')
            // some unique things that should be set on the child
            ->setDefault('_locale', 'fr')
            ->setRequirement('_locale', 'fr|en')
            ->setOption('niceRoute', true)
            ->setSchemes(['http'])
            ->setMethods(['GET', 'POST']);

        $collection = $routes->build();
        $actualListRoute = $collection->get('blog_list');

        $this->assertEquals(1, $actualListRoute->getDefault('page'));
        $this->assertEquals('\d+', $actualListRoute->getRequirement('id'));
        $this->assertTrue($actualListRoute->getOption('expose'));
        // none of these should be overridden
        $this->assertEquals('html', $actualListRoute->getDefault('_format'));
        $this->assertEquals('json|xml', $actualListRoute->getReq