ntent());

        $req3 = Request::create('/test', 'get', [], [], [], ['HTTP_FOO' => 'Foo', 'HTTP_BAR' => 'Bar']);
        $res3 = new Response('test 3', 200, ['Vary' => 'Foo Bar']);
        $key = $this->store->write($req3, $res3);
        $this->assertEquals($this->getStorePath('en'.hash('sha256', 'test 3')), $this->store->lookup($req3)->getContent());

        $this->assertCount(2, $this->getStoreMetadata($key));
    }

    public function testLocking()
    {
        $req = Request::create('/test', 'get', [], [], [], ['HTTP_FOO' => 'Foo', 'HTTP_BAR' => 'Bar']);
        $this->assertTrue($this->store->lock($req));

        $path = $this->store->lock($req);
        $this->assertTrue($this->store->isLocked($req));

        $this->store->unlock($req);
        $this->assertFalse($this->store->isLocked($req));
    }

    public function testPurgeHttps()
    {
        $request = Request::create('https://example.com/foo');
        $this->store->write($request, new Response('foo'));

        $this->assertNotEmpty($this->getStoreMetadata($request));

        $this->assertTrue($this->store->purge('https://example.com/foo'));
        $this->assertEmpty($this->getStoreMetadata($request));
    }

    public function testPurgeHttpAndHttps()
    {
        $requestHttp = Request::create('https://example.com/foo');
        $this->store->write($requestHttp, new Response('foo'));

        $requestHttps = Request::create('http://example.com/foo');
        $this->store->write($requestHttps, new Response('foo'));

        $this->assertNotEmpty($this->getStoreMetadata($requestHttp));
        $this->assertNotEmpty($this->getStoreMetadata($requestHttps));

        $this->assertTrue($this->store->purge('http://example.com/foo'));
        $this->assertEmpty($this->getStoreMetadata($requestHttp));
        $this->assertEmpty($this->getStoreMetadata($requestHttps));
    }

    protected function storeSimpleEntry($path = null, $headers = [])
    {
        if (null === $path) {
            $path = '/test';
        }

        $this->request = Request::create($path, 'get', [], [], [], $headers);
        $this->response = new Response('test', 200, ['Cache-Control' => 'max-age=420']);

        return $this->store->write($this->request, $this->response);
    }

    protected function getStoreMetadata($key)
    {
        $r = new \ReflectionObject($this->store);
        $m = $r->getMethod('getMetadata');
        $m->setAccessible(true);

        if ($key instanceof Request) {
            $m1 = $r->getMethod('getCacheKey');
            $m1->setAccessible(true);
            $key = $m1->invoke($this->store, $key);
        }

        return $m->invoke($this->store, $key);
    }

    protected function getStorePath($key)
    {
        $r = new \ReflectionObject($this->store);
        $m = $r->getMethod('getPath');
        $m->setAccessible(true);

        return $m->invoke($this