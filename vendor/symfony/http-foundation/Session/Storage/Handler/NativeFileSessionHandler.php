

        $this->assertEquals('http://servername/path/info?query=string', $request->getUri(), '->getUri() with rewrite, default port without HOST_HEADER');

        // With encoded characters

        $server = [
            'HTTP_HOST' => 'host:8080',
            'SERVER_NAME' => 'servername',
            'SERVER_PORT' => '8080',
            'QUERY_STRING' => 'query=string',
            'REQUEST_URI' => '/ba%20se/index_dev.php/foo%20bar/in+fo?query=string',
            'SCRIPT_NAME' => '/ba se/index_dev.php',
            'PATH_TRANSLATED' => 'redirect:/index.php/foo bar/in+fo',
            'PHP_SELF' => '/ba se/index_dev.php/path/info',
            'SCRIPT_FILENAME' => '/some/where/ba se/index_dev.php',
        ];

        $request->initialize([], [], [], [], [], $server);

        $this->assertEquals(
            'http://host:8080/ba%20se/index_dev.php/foo%20bar/in+fo?query=string',
            $request->getUri()
        );

        // with user info

        $server['PHP_AUTH_USER'] = 'fabien';
        $request->initialize([], [], [], [], [], $server);
        $this->assertEquals('http://host:8080/ba%20se/index_dev.php/foo%20bar/in+fo?query=string', $request->getUri());

        $server['PHP_AUTH_PW'] = 'symfony';
        $request->initialize([], [], [], [], [], $server);
        $this->assertEquals('http://host:8080/ba%20se/index_dev.php/foo%20bar/in+fo?query=string', $request->getUri());
    }

    public function testGetUriForPath()
    {
        $request = Request::create('http://test.com/foo?bar=baz');
        $this->assertEquals('http://test.com/some/path', $request->getUriForPath('/some/path'));

        $request = Request::create('http://test.com:90/foo?bar=baz');
        $this->assertEquals('http://test.com:90/some/path', $request->getUriForPath('/some/path'));

        $request = Requ