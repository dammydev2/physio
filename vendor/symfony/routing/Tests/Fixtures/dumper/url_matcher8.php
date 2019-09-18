d('a', new Route('/a'));
        $subColl->add('b', new Route('/b'));
        $subColl->add('c', new Route('/c'));
        $subColl->addPrefix('/p');
        $coll->addCollection($subColl);

        $coll->add('baz', new Route('/{baz}'));

        $subColl = new RouteCollection();
        $subColl->add('buz', new Route('/buz'));
        $subColl->addPrefix('/prefix');
        $coll->addCollection($subColl);

        $matcher = $this->getUrlMatcher($coll);
        $this->assertEquals(['_route' => 'a'], $matcher->match('/p/a'));
        $this->assertEquals(['_route' => 'baz', 'baz' => 'p'], $matcher->match('/p'));
        $this->assertEquals(['_route' => 'buz'], $matcher->match('/prefix/buz'));
    }

    /**
     * @expectedException \Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function testSchemeAndMethodMismatch()
    {
        $coll = new RouteCollection();
        $coll->add('foo', new Route('/', [], [], [], null, ['https'], ['POST']));

    