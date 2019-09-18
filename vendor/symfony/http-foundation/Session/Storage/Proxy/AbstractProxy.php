oxy trusting
        Request::setTrustedProxies([], Request::HEADER_X_FORWARDED_ALL);
        $this->assertEquals('3.3.3.3', $request->getClientIp());
        $this->assertEquals('example.com', $request->getHost());
        $this->assertEquals(80, $request->getPort());
        $this->assertFalse($request->isSecure());

        // request is forwarded by a non-trusted proxy
        Request::setTrustedProxies(['2.2.2.2'], Request::HEADER_X_FORWARDED_ALL);
        $this->assertEquals('3.3.3.3', $request->getClientIp());
        $this->assertEquals('example.com', $request->getHost());
        $this->assertEquals(80, $request->getPort());
        $this->assertFalse($request->isSecure());

        // trusted proxy via setTrustedProxies()
        Request::setTrustedProxies(['3.3.3.3', '2.2.2.2'], Request::HEADER_X_FORWARDED_ALL);
        $this->assertEquals('1.1.1.1', $request->getClientIp());
        $this->assertEquals('foo.example.com', $request->getHost());
        $this->assertEquals(443, $request->getPort());
        $this->assertTrue($request->isSecure());

        // trusted proxy via setTrustedProxies()
        Request::setTrustedProxies(['3.3.3.4', '2.2.2.2'], Request::HEADER_X_FORWARDED_ALL);
        $this->assertEquals('3.3.3.3', $request->getClientIp());
        $this->assertEquals('example.com', $request->getHost());
        $this->assertEquals(80, $request->getPort());
        $this->assertFalse($request->isSecure());

        // check various X_FORWARDED_PROTO header values
        Request::setTrustedProxies(['3.3.3.3', '2.2.2.2'], Request::HEADER_X_FORWARDED_ALL);
        $request->headers->set('X_FORWARDED_PROTO', 'ssl');
        $this->assertTrue($request->isSecure());

        $request->headers->set('X_FORWARDED_PROTO', 'https, http');
        $this->assertTrue($request->isSecure());
    }

    public function testTrustedProxiesForwarded()
    {
        $request = Request::create('http://example.com/');
        $request->server->set('REMOTE_ADDR', '3.3.3.3');
        $request->headers->set('FORWARDED', 'for=1.1.1.1, host=foo.example.com:8080, proto=https, for=2.2.2.2, host=real.example.com:8080');

        // no trusted proxies
        $this->assertEquals('3.3.3.3', $request->getClientIp());
        $this->assertEquals('example.com', $request->getHost());
        $this->assertEquals(80, $r