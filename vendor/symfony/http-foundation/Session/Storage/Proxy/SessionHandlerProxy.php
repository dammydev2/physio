quest->isSecure());

        $request->headers->set('FORWARDED', 'proto=https, proto=http');
        $this->assertTrue($request->isSecure());
    }

    /**
     * @dataProvider iisRequestUriProvider
     */
    public function testIISRequestUri($headers, $server, $expectedRequestUri)
    {
        $request = new Request();
        $request->headers->replace($headers);
        $request->server->replace($server);

        $this->assertEquals($expectedRequestUri, $request->getRequestUri(), '->getRequestUri() is correct');

        $subRequestUri = '/bar/foo';
        $subRequest = Request::create($subRequestUri, 'get', [], [], [], $request->server->all());
        $this->assertEquals($subRequestUri, $subRequest->getRequestUri(), '->getRequestUri() is correct in sub request');
    }

    public function iisRequestUriProvider()
    {
        return [
            [
                [],
                [
                    'IIS_WasUrlRewritten' => '1',
                    'UNENCODED_URL' => '/foo/bar',
                ],
                '/foo/bar',
            ],
            [
                [],
                [
                    'ORIG_PATH_INFO' => '/foo/bar',
                ],
                '/foo/bar',
            ],
            [
                [],
                [
                    'ORIG_PATH_INFO' => '/foo/bar',
                    'QUERY_STRING' => 'foo=bar',
                ],
                '/foo/bar?foo=bar',
            ],
        ];
    }

    public function testTrustedHosts()
    {
        // create a request
        $request = Request::create('/');

        // no trusted host set -> no host check
        $request->headers->set('host', 'evil.com');
        $this->assertEquals('evil.com', $request->getHost());

        // add a trusted domain and all its subdomains
        Request::setTrustedHosts(['^([a-z]{9}\.)?trusted\.com$']);

        // untrusted host
        $request->headers->set('host', 'evil.com');
        try {
            $request->getHost();
            $this->fail('Request::getHost() should throw an exception when host is not trusted.');
        } catch (SuspiciousOperationException $e) {
            $this->assertEquals('Untrusted Host "evil.com".', $e->getMessage());
        }

        // trusted hosts
        $request->headers->set('host', 'trus