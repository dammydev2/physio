 ['POST']));
        $coll->add('c', new Route('/admin/api/package.{_format}', ['_format' => 'json'], [], [], '', [], ['GET']));

        $matcher = $this->getUrlMatcher($coll);
        $this->assertEquals('c', $matcher->match('/admin/api/package.json')['_route']);
    }

    public function testHostWithDot()
    {
        $coll = new RouteCollection();
        $coll->add('a', new Route('/foo', [], [], [], 'foo.example.com'));
        $coll->add('b', new Route('/bar/{baz}'));

        $matcher = $this->getUrlMatcher($coll);
        $this->assertEquals('b', $matcher->match('/bar/abc.123')['_route']);
    }

    public function testSlashVariant()
    {
        $coll = new RouteCollection();
        $coll->add('a', new Route('/foo/{bar}', [], ['bar' => '.*']));

        $matcher = $this->getUrlMatcher($coll);
      