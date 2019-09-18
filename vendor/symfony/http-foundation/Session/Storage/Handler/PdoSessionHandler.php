 $request->getSchemeAndHttpHost());
    }

    /**
     * @dataProvider getQueryStringNormalizationData
     */
    public function testGetQueryString($query, $expectedQuery, $msg)
    {
        $request = new Request();

        $request->server->set('QUERY_STRING', $query);
        $this->assertSame($expectedQuery, $request->getQueryString(), $msg);
    }

    public function getQueryStringNormalizationData()
    {
        return [
            ['foo', 'foo=', 'works with valueless parameters'],
            ['foo=', 'foo=', 'includes a dangling equal sign'],
            ['bar=&foo=bar', 'bar=&foo=bar', '->works with empty parameters'],
            ['foo=bar&bar=', 'bar=&foo=bar', 'sorts keys alphabetically'],

            // GET parameters, that are submitted from a HTML form, encode spaces as "+" by default (as defined in enctype application/x-www-form-urlencoded).
            // PHP also converts "+" to spaces when filling the global _GET or when using the function parse_str.
            ['baz=Foo%20Baz&bar=Foo+Bar', 'bar=Foo%20Bar&baz=Foo%20Baz', 'normalizes spaces in both encodings "%20" and "+"'],

            ['foo[]=1&foo[]=2', 'foo%5B0%5D=1&foo%5B1%5D=2', 'allows array notation'],
            ['foo=1&foo=2', 'foo=2', 'merges repeated parameters'],
            ['pa%3Dram=foo%26bar%3Dbaz&test=test', 'pa%3Dram=foo%26bar%3Dbaz&test=test', 'works with encoded delimiters'],
            ['0', '0=', 'allows "0"'],
            ['Foo Bar&Foo%20Baz', 'Foo_Bar=&Foo_Baz=', 'normalizes encoding in keys'],
            ['bar=Foo Bar&baz=Foo%20Baz', 'bar=Foo%20Bar&baz=Foo%20Baz', 'normalizes encoding in values'],
            ['foo=bar&&&test&&', 'foo=bar&test=', 'removes unneeded delimiters'],
            ['formula=e=m*c^2', 'formula=e%3Dm%2Ac%5E2', 'correctly treats only the first "=" as delimiter and the next as value'],

            // Ignore pairs with empty key, even if there was a value, e.g. "=value", as such nameless values cannot be retrieved anyway.
            // PHP also does not include them when building _GET.
            ['foo=bar&=a=b&=x=y', 'foo=bar', 'removes params with empty key'],

            // Don't reorder nested query string keys
            ['foo[]=Z&foo[]=A', 'foo%5B0%5D=Z&foo%5B1%5D=A', 'keeps order of values'],
            ['foo[Z]=B&foo[A]=B', 'foo%5BZ%5D=B&foo%5BA%5D=B', 'keeps order of keys'],

            ['utf8=✓', 'utf8=%E2%9C%93', 'encodes UTF-8'],
        ];
    }

    public function testGetQueryStringReturnsNull()
    {
        $request = new Request();

        $this->assertNull($request->getQueryString(), '->getQueryString() returns null for non-existent query string');

        $request->server->set('QUERY_STRING', '');
        $this->assertNull($request->getQueryString(), '->getQueryString() returns null for empty query string');
    }

    public function testGetHost()
    {
        $request = new Request();

        $request->initialize(['foo' => 'bar']);
        $this->assertEquals('', $request->getHost(), '->getHost() return empty string if not initialized');

        $request->initialize([], [], [], [], [], ['HTTP_HOST' => 'www.example.com']);
        $this->assertEquals('www.example.com', $request->getHost(), '->getHost() from Host Header');

        // Host header with port number
        $request->initialize([], [], [], [], [], ['HTTP_HOST' => 'www.example.com:8080']);
        $this->assertEquals('www.example.com', $request->getHost(), '->getHost() from Host Header with port number');

        // Server values
        $request->initialize([], [], [], [], [], ['SERVER_NAME' => 'www.example.com']);
        $this->assertEquals('www.example.com', $request->getHost(), '->getHost() from server name');

        $request->initialize([], [], [], [], [], ['SERVER_NAME' => 'www.example.com', 'HTTP_HOST' => 'www.host.com']);
        $this->assertEquals('www.host.com', $request->getHost(), '->getHost() value from Host header has priority over SERVER_NAME ');
    }

    public function testGetPort()
    {
        $request = Request::create('http://example.com', 'GET', [], [], [], [
            'HTTP_X_FORWARDED_PROTO' => 'https',
            'HTTP_X_FORWARDED_PORT' => '443',
        ]);
        $port = $request->getPort();

        $this->assertEquals(80, $port, 'Without trusted proxies FORWARDED_PROTO and FORWARDED_PORT are ignored.');

        Request::setTrustedProxies(['1.1.1.1'], Request::HEADER_X_FORWARDED_ALL);
        $request = Request::create('http://example.com', 'GET', [], [], [], [
            'HTTP_X_FORWARDED_PROTO' => 'https',
            'HTTP_X_FORWARDED_PORT' => '8443',
        ]);
        $this->assertEquals(80, $request->getPort(), 'With PROTO and PORT on untrusted connection server value takes precedence.');
        $request->server->set('REMOTE_ADDR', '1.1.1.1');
        $this->assertEquals(8443, $request->getPort(), 'With PROTO and PORT set PORT takes precedence.');

        $request = Request::create('http://example.com', 'GET', [], [], [], [
            'HTTP_X_FORWARDED_PROTO' => 'https',
        ]);
        $this->assertEquals(80, $request->getPort(), 'With only PROTO set getPort() ignores trusted headers on untrusted connection.');
        $request->server->set('REMOTE_ADDR', '1.1.1.1');
        $this->assertEquals(443, $request->getPort(), 'With only PROTO set getPort() defaults to 443.');

        $request = Request::create('http://example.com', 'GET', [], [], [], [
            'HTTP_X_FORWARDED_PROTO' => 'http',
        ]);
        $this->assertEquals(80, $request->getPort(), 'If X_FORWARDED_PROTO is set to HTTP getPort() ignores trusted headers on untrusted connection.');
        $request->server->set('REMOTE_ADDR', '1.1.1.1');
        $this->assertEquals(80, $request->getPort(), 'If X_FORWARDED_PROTO is set to HTTP getPort() returns port of the original request.');

        $request = Request::create('http://example.com', 'GET', [], [], [], [
            'HTTP_X_FORWARDED_PROTO' => 'On',
        ]);
        $this->assertEquals(80, $request->getPort(), 'With only PROTO set and value is On, getPort() ignores trusted headers on untrusted connection.');
        $request->server->set('REMOTE_ADDR', '1.1.1.1');
        $this->assertEquals(443, $request->getPort(), 'With only PROTO set and value is On, getPort() defaults to 443.');

        $request = Request::create('http://example.com', 'GET', [], [], [], [
            'HTTP_X_FORWARDED_PROTO' => '1',
        ]);
        $this->assertEquals(80, $request->getPort(), 'With only PROTO set and value is 1, getPort() ignores trusted headers on untrusted connection.');
        $request->server->set('REMOTE_ADDR', '1.1.1.1');
        $this->assertEquals(443, $request->getPort(), 'With only PROTO set and value is 1, getPort() defaults to 443.');

        $request = Request::create('http://example.com', 'GET', [], [], [], [
            'HTTP_X_FORWARDED_PROTO' => 'something-else',
        ]);
        $port = $request->getPort();
        $this->assertEquals(80, $port, 'With only PROTO set and value is not recognized, getPort() defaults to 80.');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetHostWithFakeHttpHostValue()
    {
        $request = new Request();
        $request->initialize([], [], [], [], [], ['HTTP_HOST' => 'www.host.com?query=string']);
        $request->getHost();
    }

    public function testGetSetMethod()
    {
        $request = new Request();

        $this->assertEquals('GET', $request->getMethod(), '->getMethod() returns GET if no method is defined');

        $request->setMethod('get');
        $this->assertEquals('GET', $request->getMethod(), '->getMethod() returns an uppercased string');

        $request->setMethod('PURGE');
        $this->assertEquals('PURGE', $request->getMethod(), '->getMethod() returns the method even if it is not a standard one');

        $request->setMethod('POST');
        $this->assertEquals('POST', $request->getMethod(), '->getMethod() returns the method POST if no _method is defined');

        $request->setMethod('POST');
        $request->request->set('_method', 'purge');
        $this->assertEquals('POST', $request->getMethod(), '->getMethod() does not return the method from _method if defined and POST but support not enabled');

        $request = new Request();
        $request->setMethod('POST');
        $request->request->set('_method', 'purge');

        $this->assertFalse(Request::getHttpMethodParameterOverride(), 'httpMethodParameterOverride should be disabled by default');

        Request::enableHttpMethodParameterOverride();

        $this->assertTrue(Request::getHttpMethodParameterOverride(), 'httpMethodParameterOverride should be enabled now but it is not');

        $this->assertEquals('PURGE', $request->getMethod(), '->getMethod() returns the method from _method if defined and POST');
        $this->disableHttpMethodParameterOverride();

        $request = new Request();
        $request->setMethod('POST');
        $request->query->set('_method', 'purge');
        $this->assertEquals('POST', $request->getMethod(), '->getMethod() does not return the method from _method if defined and POST but support not enabled');

        $request = new Request();
        $request->setMethod('POST');
        $request->query->set('_method', 'purge');
        Request::enableHttpMethodParameterOverride();
        $this->assertEquals('PURGE', $request->getMethod(), '->getMethod() returns the method from _method if defined and POST');
        $this->disableHttpMethodParameterOverride();

        $request = new Request();
        $request->setMethod('POST');
        $request->headers->set('X-HTTP-METHOD-OVERRIDE', 'delete');
        $this->assertEquals('DELETE', $request->getMethod(), '->getMethod() returns the method from X-HTTP-Method-Override even though _method is set if defined and POST');

        $request = new Request();
        $request->setMethod('POST');
        $request->headers->set('X-HTTP-METHOD-OVERRIDE', 'delete');
        $this->assertEquals('DELETE', $request->getMethod(), '->getMethod() returns the method from X-HTTP-Method-Override if defined and POST');

        $request = new Request();
        $request->setMethod('POST');
        $request->query->set('_method', ['delete', 'patch']);
        $this->assertSame('POST', $request->getMethod(), '->getMethod() returns the request method if invalid type is defined in query');
    }

    /**
     * @dataProvider getClientIpsProvider
     */
    public function testGetClientIp($expected, $remoteAddr, $httpForwardedFor, $trustedProxies)
    {
        $request = $this->getRequestInstanceForClientIpTests($remoteAddr, $httpForwardedFor, $trustedProxies);

        $this->assertEquals($expected[0], $request->getClientIp());
    }

    /**
     * @dataProvider getClientIpsProvider
     */
    public function testGetClientIps($expected, $remoteAddr, $httpForwardedFor, $trustedProxies)
    {
        $request = $this->getRequestInstanceForClientIpTests($remoteAddr, $httpForwardedFor, $trustedProxies);

        $this->assertEquals($expected, $request->getClientIps());
    }

    /**
     * @dataProvider getClientIpsForwardedProvider
     */
    public function testGetClientIpsForwarded($expected, $remoteAddr, $httpForwarded, $trustedProxies)
    {
        $request = $this->getRequestInstanceForClientIpsForwardedTests($remoteAddr, $httpForwarded, $trustedProxies);

        $this->assertEquals($expected, $request->getClientIps());
    }

    public function getClientIpsForwardedProvider()
    {
        //              $expected                                  $remoteAddr  $httpForwarded                                       $trustedProxies
        return [
            [['127.0.0.1'],                              '127.0.0.1', 'for="_gazonk"',                                      null],
            [['127.0.0.1'],                              '127.0.0.1', 'for="_gazonk"',                                      ['127.0.0.1']],
            [['88.88.88.88'],                            '127.0.0.1', 'for="88.88.88.88:80"',                               ['127.0.0.1']],
            [['192.0.2.60'],                             '::1',       'for=192.0.2.60;proto=http;by=203.0.113.43',          ['::1']],
            [['2620:0:1cfe:face:b00c::3', '192.0.2.43'], '::1',       'for=192.0.2.43, for="[2620:0:1cfe:face:b00c::3]"',   ['::1']],
            [['2001:db8:cafe::17'],                      '::1',       'for="[2001:db8:cafe::17]:4711',                      ['::1']],
        ];
    }

    public function getClientIpsProvider()
    {
        //        $expected                          $remoteAddr                 $httpForwardedFor            $trustedProxies
        return [
            // simple IPv4
            [['88.88.88.88'],              '88.88.88.88',              null,                        null],
            // trust the IPv4 remote addr
            [['88.88.88.88'],              '88.88.88.88',              null,                        ['88.88.88.88']],

            // simple IPv6
            [['::1'],                      '::1',                      null,                        null],
            // trust the IPv6 remote addr
            [['::1'],                      '::1',                      null,                        ['::1']],

            // forwarded for with remote IPv4 addr not trusted
            [['127.0.0.1'],                '127.0.0.1',                '88.88.88.88',               null],
            // forwarded for with remote IPv4 addr trusted + comma
            [['88.88.88.88'],              '127.0.0.1',                '88.88.88.88,',              ['127.0.0.1']],
            // forwarded for with remote IPv4 and all FF addrs trusted
            [['88.88.88.88'],              '127.0.0.1',                '88.88.88.88',               ['127.0.0.1', '88.88.88.88']],
            // forwarded for with remote IPv4 range trusted
            [['88.88.88.88'],              '123.45.67.89',             '88.88.88.88',               ['123.45.67.0/24']],

            // forwarded for with remote IPv6 addr not trusted
            [['1620:0:1cfe:face:b00c::3'], '1620:0:1cfe:face:b00c::3', '2620:0:1cfe:face:b00c::3',  null],
            // forwarded for with remote IPv6 addr trusted
            [['2620:0:1cfe:face:b00c::3'], '1620:0:1cfe:face:b00c::3', '2620:0:1cfe:face:b00c::3',  ['1620:0:1cfe:face:b00c::3']],
            // forwarded for with remote IPv6 range trusted
            [['88.88.88.88'],              '2a01:198:603:0:396e:4789:8e99:890f', '88.88.88.88',     ['2a01:198:603:0::/65']],

            // multiple forwarded for with remote IPv4 addr trusted
            [['88.88.88.88', '87.65.43.21', '127.0.0.1'], '123.45.67.89', '127.0.0.1, 87.65.43.21, 88.88.88.88', ['123.45.67.89']],
            // multiple forwarded for with remote IPv4 addr and some reverse proxies trusted
            [['87.65.43.21', '127.0.0.1'], '123.45.67.89',             '127.0.0.1, 87.65.43.21, 88.88.88.88', ['123.45.67.89', '88.88.88.88']],
            // multiple forwarded for with remote IPv4 addr and some reverse proxies trusted but in the middle
            [['88.88.88.88', '127.0.0.1'], '123.45.67.89',             '127.0.0.1, 87.65.43.21, 88.88.88.88', ['123.45.67.89', '87.65.43.21']],
            // multiple forwarded for with remote IPv4 addr and all reverse proxies trusted
            [['127.0.0.1'],                '123.45.67.89',             '127.0.0.1, 87.65.43.21, 88.88.88.88', ['123.45.67.89', '87.65.43.21', '88.88.88.88', '127.0.0.1']],

            // multiple forwarded for with remote IPv6 addr trusted
            [['2620:0:1cfe:face:b00c::3', '3620:0:1cfe:face:b00c::3'], '1620:0:1cfe:face:b00c::3', '3620:0:1cfe:face:b00c::3,2620:0:1cfe:face:b00c::3', ['1620:0:1cfe:face:b00c::3']],
            // multiple forwarded for with remote IPv6 addr and some reverse proxies trusted
            [['3620:0:1cfe:face:b00c::3'], '1620:0:1cfe:face:b00c::3', '3620:0:1cfe:face:b00c::3,2620:0:1cfe:face:b00c::3', ['1620:0:1cfe:face:b00c::3', '2620:0:1cfe:face:b00c::3']],
            // multiple forwarded for with remote IPv4 addr and some reverse proxies trusted but in the middle
            [['2620:0:1cfe:face:b00c::3', '4620:0:1cfe:face:b00c::3'], '1620:0:1cfe:face:b00c::3', '4620:0:1cfe:face:b00c::3,3620:0:1cfe:face:b00c::3,2620:0:1cfe:face:b00c::3', ['1620:0:1cfe:face:b00c::3', '3620:0:1cfe:face:b00c::3']],

            // client IP with port
            [['88.88.88.88'], '127.0.0.1', '88.88.88.88:12345, 127.0.0.1', ['127.0.0.1']],

            // invalid forwarded IP is ignored
            [['88.88.88.88'], '127.0.0.1', 'unknown,88.88.88.88', ['127.0.0.1']],
            [['88.88.88.88'], '127.0.0.1', '}__test|O:21:&quot;JDatabaseDriverMysqli&quot;:3:{s:2,88.88.88.88', ['127.0.0.1']],
        ];
    }

    /**
     * @expectedException \Symfony\Component\HttpFoundation\Exception\ConflictingHeadersException
     * @dataProvider getClientIpsWithConflictingHeadersProvider
     */
    public function testGetClientIpsWithConflictingHeaders($httpForwarded, $httpXForwardedFor)
    {
        $request = new Request();

        $server = [
            'REMOTE_ADDR' => '88.88.88.88',
            'HTTP_FORWARDED' => $httpForwarded,
            'HTTP_X_FORWARDED_FOR' => $httpXForwardedFor,
        ];

        Request::setTrustedProxies(['88.88.88.88'], Request::HEADER_X_FORWARDED_ALL | Request::HEADER_FORWARDED);

        $request->initialize([], [], [], [], [], $server);

        $request->getClientIps();
    }

    /**
     * @dataProvider getClientIpsWithConflictingHeadersProvider
     */
    public function testGetClientIpsOnlyXHttpForwardedForTrusted($httpForwarded, $httpXForwardedFor)
    {
        $request = new Request();

        $server = [
            'REMOTE_ADDR' => '88.88.88.88',
            'HTTP_FORWARDED' => $httpForwarded,
            'HTTP_X_FORWARDED_FOR' => $httpXForwardedFor,
        ];

        Request::setTrustedProxies(['88.88.88.88'], Request::HEADER_X_FORWARDED_FOR);

        $request->initialize([], [], [], [], [], $server);

        $this->assertSame(array_reverse(explode(',', $httpXForwardedFor)), $request->getClientIps());
    }

    public function getClientIpsWithConflictingHeadersProvider()
    {
        //        $httpForwarded                   $httpXForwardedFor
        return [
            ['for=87.65.43.21',                 '192.0.2.60'],
            ['for=87.65.43.21, for=192.0.2.60', '192.0.2.60'],
            ['for=192.0.2.60',                  '192.0.2.60,87.65.43.21'],
            ['for="::face", for=192.0.2.60',    '192.0.2.60,192.0.2.43'],
            ['for=87.65.43.21, for=192.0.2.60', '192.0.2.60,87.65.43.21'],
        ];
    }

    /**
     * @dataProvider getClientIpsWithAgreeingHeadersProvider
     */
    public function testGetClientIpsWithAgreeingHeaders($httpForwarded, $httpXForwardedFor, $expectedIps)
    {
        $request = new Request();

        $server = [
            'REMOTE_ADDR' => '88.88.88.88',
            'HTTP_FORWARDED' => $httpForwarded,
            'HTTP_X_FORWARDED_FOR' => $httpXForwardedFor,
        ];

        Request::setTrustedProxies(['88.88.88.88'], -1);

        $request->initialize([], [], [], [], [], $server);

        $clientIps = $request->getClientIps();

        $this->assertSame($expectedIps, $clientIps);
    }

    public function getClientIpsWithAgreeingHeadersProvider()
    {
        //        $httpForwarded                               $httpXForwardedFor
        return [
            ['for="192.0.2.60"',                          '192.0.2.60',             ['192.0.2.60']],
            ['for=192.0.2.60, for=87.65.43.21',           '192.0.2.60,87.65.43.21', ['87.65.43.21', '192.0.2.60']],
            ['for="[::face]", for=192.0.2.60',            '::face,192.0.2.60',      ['192.0.2.60', '::face']],
            ['for="192.0.2.60:80"',                       '192.0.2.60',             ['192.0.2.60']],
            ['for=192.0.2.60;proto=http;by=203.0.113.43', '192.0.2.60',             ['192.0.2.60']],
            ['for="[2001:db8:cafe::17]:4711"',            '2001:db8:cafe::17',      ['2001:db8:cafe::17']],
        ];
    }

    public function testGetContentWorksTwiceInDefaultMode()
    {
        $req = new Request();
        $this->assertEquals('', $req->getContent());
        $this->assertEquals('', $req->getContent());
    }

    public function testGetContentReturnsResource()
    {
        $req = new Request();
        $retval = $req->getContent(true);
        $this->assertInternalType('resource', $retval);
        $this->assertEquals('', fread($retval, 1));
        $this->assertTrue(feof($retval));
    }

    public function testGetContentReturnsResourceWhenContentSetInConstructor()
    {
        $req = new Request([], [], [], [], [], [], 'MyContent');
        $resource = $req->getContent(true);

        $this->assertInternalType('resource', $resource);
        $this->assertEquals('MyContent', stream_get_contents($resource));
    }

    public function testContentAsResource()
    {
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, 'My other content');
        rewind($resource);

        $req = new Request([], [], [], [], [], [], $resource);
        $this->assertEquals('My other content', stream_get_contents($req->getContent(true)));
        $this->assertEquals('My other content', $req->getContent());
    }

    public function getContentCantBeCalledTwiceWithResourcesProvider()
    {
        return [
            'Resource then fetch' => [true, false],
            'Resource then resource' => [true, true],
        ];
    }

    /**
     * @dataProvider getContentCanBeCalledTwiceWithResourcesProvider
     */
    public function testGetContentCanBeCalledTwiceWithResources($first, $second)
    {
        $req = new Request();
        $a = $req->getContent($first);
        $b = $req->getContent($second);

        if ($first) {
            $a = stream_get_contents($a);
        }

        if ($second) {
            $b = stream_get_contents($b);
        }

        $this->assertSame($a, $b);
    }

    public function getContentCanBeCalledTwiceWithResourcesProvider()
    {
        return [
            'Fetch then fetch' => [false, false],
            'Fetch then resource' => [false, true],
            'Resource then fetch' => [true, false],
            'Resource then resource' => [true, true],
        ];
    }

    public function provideOverloadedMethods()
    {
        return [
            ['PUT'],
            ['DELETE'],
            ['PATCH'],
            ['put'],
            ['delete'],
            ['patch'],
        ];
    }

    /**
     * @dataProvider provideOverloadedMethods
     */
    public function testCreateFromGlobals($method)
    {
        $normalizedMethod = strtoupper($method);

        $_GET['foo1'] = 'bar1';
        $_POST['foo2'] = 'bar2';
        $_COOKIE['foo3'] = 'bar3';
        $_FILES['foo4'] = ['bar4'];
        $_SERVER['foo5'] = 'bar5';

        $request = Request::createFromGlobals();
        $this->assertEquals('bar1', $request->query->get('foo1'), '::fromGlobals() uses values from $_GET');
        $this->assertEquals('bar2', $request->request->get('foo2'), '::fromGlobals() uses values from $_POST');
        $this->assertEquals('bar3', $request->cookies->get('foo3'), '::fromGlobals() uses values from $_COOKIE');
        $this->assertEquals(['bar4'], $request->files->get('foo4'), '::fromGlobals() uses values from $_FILES');
        $this->assertEquals('bar5', $request->server->get('foo5'), '::fromGlobals() uses values from $_SERVER');

        unset($_GET['foo1'], $_POST['foo2'], $_COOKIE['foo3'], $_FILES['foo4'], $_SERVER['foo5']);

        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';
        $request = RequestContentProxy::createFromGlobals();
        $this->assertEquals($normalizedMethod, $request->getMethod());
        $this->assertEquals('mycontent', $request->request->get('content'));

        unset($_SERVER['REQUEST_METHOD'], $_SERVER['CONTENT_TYPE']);

        Request::createFromGlobals();
        Request::enableHttpMethodParameterOverride();
        $_POST['_method'] = $method;
        $_POST['foo6'] = 'bar6';
        $_SERVER['REQUEST_METHOD'] = 'PoSt';
        $request = Request::createFromGlobals();
        $this->assertEquals($normalizedMethod, $request->getMethod());
        $this->assertEquals('POST', $request->getRealMethod());
        $this->assertEquals('bar6', $request->request->get('foo6'));

        unset($_POST['_method'], $_POST['foo6'], $_SERVER['REQUEST_METHOD']);
        $this->disableHttpMethodParameterOverride();
    }

    public function testOverrideGlobals()
    {
        $request = new Request();
        $request->initialize(['foo' => 'bar']);

        // as the Request::overrideGlobals really work, it erase $_SERVER, so we must backup it
        $server = $_SERVER;

        $request->overrideGlobals();

        $this->assertEquals(['foo' => 'bar'], $_GET);

        $request->initialize([], ['foo' => 'bar']);
        $request->overrideGlobals();

        $this->assertEquals(['foo' => 'bar'], $_POST);

        $this->assertArrayNotHasKey('HTTP_X_FORWARDED_PROTO', $_SERVER);

        $request->headers->set('X_FORWARDED_PROTO', 'https');

        Request::setTrustedProxies(['1.1.1.1'], Request::HEADER_X_FORWARDED_ALL);
        $this->assertFalse($request->isSecure());
        $request->server->set('REMOTE_ADDR', '1.1.1.1');
        $this->assertTrue($request->isSecure());

        $request->overrideGlobals();

        $this->assertArrayHasKey('HTTP_X_FORWARDED_PROTO', $_SERVER);

        $request->headers->set('CONTENT_TYPE', 'multipart/form-data');
        $request->headers->set('CONTENT_LENGTH', 12345);

        $request->overrideGlobals();

        $this->assertArrayHasKey('CONTENT_TYPE', $_SERVER);
        $this->assertArrayHasKey('CONTENT_LENGTH', $_SERVER);

        $request->initialize(['foo' => 'bar', 'baz' => 'foo']);
        $request->query->remove('baz');

        $request->overrideGlobals();

        $this->assertEquals(['foo' => 'bar'], $_GET);
        $this->assertEquals('foo=bar', $_SERVER['QUERY_STRING']);
        $this->assertEquals('foo=bar', $request->server->get('QUERY_STRING'));

        // restore initial $_SERVER array
        $_SERVER = $server;
    }

    public function testGetScriptName()
    {
        $request = new Request();
        $this->assertEquals('', $request->getScriptName());

        $server = [];
        $server['SCRIPT_NAME'] = '/index.php';

        $request->initialize([], [], [], [], [], $server);

        $this->assertEquals('/index.php', $request->getScriptName());

        $server = [];
        $server['ORIG_SCRIPT_NAME'] = '/frontend.php';
        $request->initialize([], [], [], [], [], $server);

        $this->assertEquals('/frontend.php', $request->getScriptName());

        $server = [];
        $server['SCRIPT_NAME'] = '/index.php';
        $server['ORIG_SCRIPT_NAME'] = '/frontend.php';
        $request->initialize([], [], [], [], [], $server);

        $this->assertEquals('/index.php', $request->getScriptName());
    }

    public function testGetBasePath()
    {
        $request = new Request();
        $this->assertEquals('', $request->getBasePath());

        $server = [];
        $server['SCRIPT_FILENAME'] = '/some/where/index.php';
        $request->initialize([], [], [], [], [], $server);
        $this->assertEquals('', $request->getBasePath());

        $server = [];
        $server['SCRIPT_FILENAME'] = '/some/where/index.php';
        $server['SCRIPT_NAME'] = '/index.php';
        $request->initialize([], [], [], [], [], $server);

        $this->assertEquals('', $request->getBasePath());

        $server = [];
        $server['SCRIPT_FILENAME'] = '/some/where/index.php';
        $server['PHP_SELF'] = '/index.php';
        $request->initialize([], [], [], [], [], $server);

        $this->assertEquals('', $request->getBasePath());

        $server = [];
        $server['SCRIPT_FILENAME'] = '/some/where/index.php';
        $server['ORIG_SCRIPT_NAME'] = '/index.php';
        $request->initialize([], [], [], [], [], $server);

        $this->assertEquals('', $request->getBasePath());
    }

    public function testGetPathInfo()
    {
        $request = new Request();
        $this->assertEquals('/', $request->getPathInfo());

        $server = [];
        $server['REQUEST_URI'] = '/path/info';
        $request->initialize([], [], [], [], [], $server);

        $this->assertEquals('/path/info', $request->getPathInfo());

        $server = [];
        $server['REQUEST_URI'] = '/path%20test/info';
        $request->initialize([], [], [], [], [], $server);

        $this->assertEquals('/path%20test/info', $request->getPathInfo());

        $server = [];
        $server['REQUEST_URI'] = '?a=b';
        $request->initialize([], [], [], [], [], $server);

        $this->assertEquals('/', $request->getPathInfo());
    }

    public function testGetParameterPrecedence()
    {
        $request = new Request();
        $request->attributes->set('foo', 'attr');
        $request->query->set('foo', 'query');
        $request->request->set('foo', 'body');

        $this->assertSame('attr', $request->get('foo'));

        $request->attributes->remove('foo');
        $this->assertSame('query', $request->get('foo'));

        $request->query->remove('foo');
        $this->assertSame('body', $request->get('foo'));

        $request->request->remove('foo');
        $this->assertNull($request->get('foo'));
    }

    public function testGetPreferredLanguage()
    {
        $request = new Request();
        $this->assertNull($request->getPreferredLanguage());
        $this->assertNull($request->getPreferredLanguage([]));
        $this->assertEquals('fr', $request->getPreferredLanguage(['fr']));
        $this->assertEquals('fr', $request->getPreferredLanguage(['fr', 'en']));
        $this->assertEquals('en', $request->getPreferredLanguage(['en', 'fr']));
        $this->assertEquals('fr-ch', $request->getPreferredLanguage(['fr-ch', 'fr-fr']));

        $request = new Request();
        $request->headers->set('Accept-language', 'zh, en-us; q=0.8, en; q=0.6');
        $this->assertEquals('en', $request->getPreferredLanguage(['en', 'en-us']));

        $request = new Request();
        $request->headers->set('Accept-language', 'zh, en-us; q=0.8, en; q=0.6');
        $this->assertEquals('en', $request->getPreferredLanguage(['fr', 'en']));

        $request = new Request();
        $request->headers->set('Accept-language', 'zh, en-us; q=0.8');
        $this->assertEquals('en', $request->getPreferredLanguage(['fr', 'en']));

        $request = new Request();
        $request->headers->set('Accept-language', 'zh, en-us; q=0.8, fr-fr; q=0.6, fr; q=0.5');
        $this->assertEquals('en', $request->getPreferredLanguage(['fr', 'en']));
    }

    public function testIsXmlHttpRequest()
    {
        $request = new Request();
        $this->assertFalse($request->isXmlHttpRequest());

        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $this->assertTrue($request->isXmlHttpRequest());

        $request->headers->remove('X-Requested-With');
        $this->assertFalse($request->isXmlHttpRequest());
    }

    /**
     * @requires extension intl
     */
    public function testIntlLocale()
    {
        $request = new Request();

        $request->setDefaultLocale('fr');
        $this->assertEquals('fr', $request->getLocale());
        $this->assertEquals('fr', \Locale::getDefault());

        $request->setLocale('en');
        $this->assertEquals('en', $request->getLocale());
        $this->assertEquals('en', \Locale::getDefault());

        $request->setDefaultLocale('de');
        $this->assertEquals('en', $request->getLocale());
        $this->assertEquals('en', \Locale::getDefault());
    }

    public function testGetCharsets()
    {
        $request = new Request();
        $this->assertEquals([], $request->getCharsets());
        $request->headers->set('Accept-Charset', 'ISO-8859-1, US-ASCII, UTF-8; q=0.8, ISO-10646-UCS-2; q=0.6');
        $this->assertEquals([], $request->getCharsets()); // testing caching

        $request = new Request();
        $request->headers->set('Accept-Charset', 'ISO-8859-1, US-ASCII, UTF-8; q=0.8, ISO-10646-UCS-2; q=0.6');
        $this->assertEquals(['ISO-8859-1', 'US-ASCII', 'UTF-8', 'ISO-10646-UCS-2'], $request->getCharsets());

        $request = new Request();
        $request->headers->set('Accept-Charset', 'ISO-8859-1,utf-8;q=0.7,*;q=0.7');
        $this->assertEquals(['ISO-8859-1', 'utf-8', '*'], $request->getCharsets());
    }

    public function testGetEncodings()
    {
        $request = new Request();
        $this->assertEquals([], $request->getEncodings());
        $request->headers->set('Accept-Encoding', 'gzip,deflate,sdch');
        $this->assertEquals([], $request->getEncodings()); // testing caching

        $request = new Request();
        $request->headers->set('Accept-Encoding', 'gzip,deflate,sdch');
        $this->assertEquals(['gzip', 'deflate', 'sdch'], $request->getEncodings());

        $request = new Request();
        $request->headers->set('Accept-Encoding', 'gzip;q=0.4,deflate;q=0.9,compress;q=0.7');
        $this->assertEquals(['deflate', 'compress', 'gzip'], $request->getEncodings());
    }

    public function testGetAcceptableContentTypes()
    {
        $request = new Request();
        $this->assertEquals([], $request->getAcceptableContentTypes());
        $request->headers->set('Accept', 'application/vnd.wap.wmlscriptc, text/vnd.wap.wml, application/vnd.wap.xhtml+xml, application/xhtml+xml, text/html, multipart/mixed, */*');
        $this->assertEquals([], $request->getAcceptableContentTypes()); // testing caching

        $request = new Request();
        $request->headers->set('Accept', 'application/vnd.wap.wmlscriptc, text/vnd.wap.wml, application/vnd.wap.xhtml+xml, application/xhtml+xml, text/html, multipart/mixed, */*');
        $this->assertEquals(['application/vnd.wap.wmlscriptc', 'text/vnd.wap.wml', 'application/vnd.wap.xhtml+xml', 'application/xhtml+xml', 'text/html', 'multipart/mixed', '*/*'], $request->getAcceptableContentTypes());
    }

    public function testGetLanguages()
    {
        $request = new Request();
        $this->assertEquals([], $request->getLanguages());

        $request = new Request();
        $request->headers->set('Accept-language', 'zh, en-us; q=0.8, en; q=0.6');
        $this->assertEquals(['zh', 'en_US', 'en'], $request->getLanguages());
        $this->assertEquals(['zh', 'en_US', 'en'], $request->getLanguages());

        $request = new Request();
        $request->headers->set('Accept-language', 'zh, en-us; q=0.6, en; q=0.8');
        $this->assertEquals(['zh', 'en', 'en_US'], $request->getLanguages()); // Test out of order qvalues

        $request = new Request();
        $request->headers->set('Accept-language', 'zh, en, en-us');
        $this->assertEquals(['zh', 'en', 'en_US'], $request->getLanguages()); // Test equal weighting without qvalues

        $request = new Request();
        $request->headers->set('Accept-language', 'zh; q=0.6, en, en-us; q=0.6');
        $this->assertEquals(['en', 'zh', 'en_US'], $request->getLanguages()); // Test equal weighting with qvalues

        $request = new Request();
        $request->headers->set('Accept-language', 'zh, i-cherokee; q=0.6');
        $this->assertEquals(['zh', 'cherokee'], $request->getLanguages());
    }

    public function testGetRequestFormat()
    {
        $request = new Request();
        $this->assertEquals('html', $request->getRequestFormat());

        // Ensure that setting different default values over time is possible,
        // aka. setRequestFormat determines the state.
        $this->assertEquals('json', $request->getRequestFormat('json'));
        $this->assertEquals('html', $request->getRequestFormat('html'));

        $request = new Request();
        $this->assertNull($request->getRequestFormat(null));

        $request = new Request();
        $this->assertNull($request->setRequestFormat('foo'));
        $this->assertEquals('foo', $request->getRequestFormat(null));

        $request = new Request(['_format' => 'foo']);
        $this->assertEquals('html', $request->getRequestFormat());
    }

