h', $length);
        $response->prepare($request);

        $this->assertEquals('', $response->getContent());
        $this->assertEquals($length, $response->headers->get('Content-Length'), 'Content-Length should be as if it was GET; see RFC2616 14.13');
    }

    public function testPrepareRemovesContentForInformationalResponse()
    {
        $response = new Response('foo');
        $request = Request::create('/');

        $response->setContent('content');
        $response->setStatusCode(101);
        $response->prepare($request);
        $this->assertEquals('', $response->getContent());
        $this->assertFalse($response->headers->has('Content-Type'));
        $this->assertFalse($response->headers->has('Content-Type'));

        $response->setContent('content');
        $response->setStatusCode(304);
        $response->prepare($request);
        $this->assertEquals('', $response->getContent());
        $this->assertFalse($response->headers->has('Content-Type'));
        $this->assertFalse($response->headers->has('Content-Length'));
    }

    public function testPrepareRemovesContentLength()
    {
        $response = new Response('foo');
        $request = Request::create('/');

        $response->headers->set('Content-Length', 12345);
        $response->prepare($request);
        $this->assertEquals(12345, $response->headers->get('Content-Length'));

        $response->headers->set('Transfer-Encoding', 'chunked');
        $response->prepare($request);
        $this->assertFalse($response->headers->has('Content-Length'));
    }

    public function testPrepareSetsPragmaOnHttp10Only()
    {
        $request = Request::create('/', 'GET');
        $request->server->set('SERVER_PROTOCOL', 'HTTP/1.0');

        $response = new Response('foo');
        $response->prepare($request);
        $this->assertEquals('no-cache', $response->headers->get('pragma'));
        $this->assertEquals('-1', $response->headers->get('expires'));

        $request->server->set('SERVER_PROTOCOL', 'HTTP/1.1');
        $response = new Response('foo');
        $response->prepare($request);
        $this->assertFalse($response->headers->has('pragma'));
        $this->assertFalse($response->headers->has('expires'));
    }

    public function testPrepareSetsCookiesSecure()
    {
        $cookie = Cookie::create('foo', 'bar');

        $response = new Response('foo');
        $response->headers->setCookie($cookie);

        $request = Request::create('/', 'GET');
        $response->prepare($request);

        $this->assertFalse($cookie->isSecure());

        $request = Request::create('https://localhost/', 'GET');
        $response->prepare($request);

        $this->a