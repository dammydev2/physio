his->assertEquals('test.com', $request->getHttpHost());
        $this->assertEquals(80, $request->getPort());
        $this->assertFalse($request->isSecure());

        $request = Request::create('https://test.com:443/foo');
        $request->server->set('REQUEST_URI', 'https://test.com:443/foo');
        $this->assertEquals('https://test.com/foo', $request->getUri());
        $this->assertEquals('/foo', $request->getPathInfo());
        $this->assertEquals('test.com', $request->getHost());
        $this->assertEquals('test.com', $request->getHttpHost());
        $this->assertEquals(443, $request->getPort());
        $this->assertTrue($request->isSecure());

        // Fragment should not be included in the URI
        $request = Request::create('http://test.com/foo#bar');
        $request->server->set('REQUEST_URI', 'http://test.com/foo#bar');
        $this->assertEquals('http://test.com/foo', $request->getUri());
    }

    /**
     * @dataProvider getRequestUriData
     */
    public function testGetRequestUri($serverRequestUri, $expected, $message)
    {
        $request = new Request();
        $request->server->add([
            'REQUEST_URI' => $serverRequestUri,

            // For having http://test.com
            'SERVER_NAME' => 'test.com',
            'SERVER_PORT' => 80,
        ]);

        $this->assertSame($expected, $request->getRequestUri(), $message);
        $this->assertSame($expected, $request->server->get('REQUEST_URI'), 'Normalize the request URI.');
    }

    public function getRequestUriData()
    {
        $message = 'Do not modify the path.';
        yield ['/foo', '/foo', $message];
        yield ['//bar/foo', '//bar/foo', $message];
        yield ['///bar/foo', '///bar/foo', $message];

        $message = 'Handle when the scheme, host are on REQUEST_URI.';
        yield ['http://test.com/foo?bar=baz', '/foo?bar=baz', $message];

        $message = 'Handle when the scheme, host and port are on REQUEST_URI.';
        yield ['http://test.com:80/foo', '/foo', $message];
        yield ['https://test.com:8080/foo', '/foo', $message];
        yield ['https://test.com:443/foo', '/foo', $message];

        $message = 'Fragment should not be included in the URI';
        yield ['http://test.com/foo#bar', '/foo', $message];
        yield ['/foo#bar', '/foo', $message];
    }

    public function testGetRequestUriWithoutRequiredHeader()
    {
        $expected = '';

        $request = new Request();

        $message = 'Fallback to empty URI when headers are missing.';
        $this->assertSame($expected, $request->getRequestUri(), $message);
        $this->assertSame($expected, $request->server->get('REQUEST_URI'), 'Normalize the request URI.');
    }

    public function testCreateCheckPrecedence()
    {
        // server is used by default
        $request = Request::create('/', 'DELETE', [], [], [], [
            'HTTP_HOST' => 'example.com',
            'HTTPS' => 'on',
            'SERVER_PORT' => 443,
            'PHP_AUTH_USER' => 'fabien',
            'PHP_AUTH_PW' => 'pa$$',
            'QUERY_STRING' => 'foo=bar',
            'CONTENT_TYPE' => 'application/json',
        ]);
        $this->assertEquals('example.com', $request->getHost());
        $this->assertEquals(443, $request->getPort());
        $this->assertTrue($request->isSecure());
        $this->assertEquals('fabien', $request->getUser());
        $this->assertEquals('pa$$', $request->getPassword());
        $this->assertEquals('', $request->getQueryString());
        $this->assertEquals('application/json', $request->headers->get('CONTENT_TYPE'));

        // URI has precedence over server
        $request = Request::create('http://thomas:pokemon@example.net:8080/?foo=bar', 'GET', [], [], [], [
            'HTTP_HOST' => 'example.com',
            'HTTPS' => 'on',
            'SERVER_PORT' => 443,
        ]);
        $this->assertEquals('example.net', $request->getHost());
        $this->assertEquals(8080, $request->getPort());
        $this->assertFalse($request->isSecure());
        $this->assertEquals('thomas', $request->getUser());
        $this->assertEquals('pokemon', $request->getPassword());
        $this->assertEquals('foo=bar', $request->getQueryString());
    }

    public function testDuplicate()
    {
        $request = new Request(['foo' => 'bar'], ['foo' => 'bar'], ['foo' => 'bar'], [], [], ['HTTP_FOO' => 'bar']);
        $dup = $request->duplicate();

        $this->assertEquals($request->query->all(), $dup->query->all(), '->duplicate() duplicates a request an copy the current query parameters');
        $this->assertEquals($request->request->all(), $dup->request->all(), '->duplicate() duplicates a request an copy the current request parameters');
        $this->assertEquals($request->attributes->all(), $dup->attributes->all(), '->duplicate() duplicates a request an copy the current attributes');
        $this->assertEquals($request->headers->all(), $dup->headers->all(), '->duplicate() duplicates a request an copy the current HTTP headers');

        $dup = $request->duplicate(['foo' => 'foobar'], ['foo' => 'foobar'], ['foo' => 'foobar'], [], [], ['HTTP_FOO' => 'foobar']);

        $this->assertEquals(['foo' => 'foobar'], $dup->query->all(), '->duplicate() overrides the query parameters if provided');
        $this->assertEquals(['foo' => 'foobar'], $dup->request->all(), '->duplicate() overrides the request parameters if provided');
        $this->assertEquals(['foo' => 'fooba