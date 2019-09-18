ySessionStorage()));
        $this->assertTrue($request->hasSession());

        $session = $request->getSession();
        $this->assertObjectHasAttribute('storage', $session);
        $this->assertObjectHasAttribute('flashName', $session);
        $this->assertObjectHasAttribute('attributeName', $session);
    }

    /**
     * @group legacy
     * @expectedDeprecation Calling "Symfony\Component\HttpFoundation\Request::getSession()" when no session has been set is deprecated since Symfony 4.1 and will throw an exception in 5.0. Use "hasSession()" instead.
     */
    public function testGetSessionNullable()
    {
        (new Request())->getSession();
    }

    public function testHasPreviousSession()
    {
        $request = new Request();

        $this->assertFalse($request->hasPreviousSession());
        $request->cookies->set('MOCKSESSID', 'foo');
        $this->assertFalse($request->hasPreviousSession());
        $request->setSession(new Session(new MockArraySessionStorage()));
        $this->assertTrue($request->hasPreviousSession());
    }

    public function testToString()
    {
        $request = new Request();

        $request->headers->set('Accept-language', 'zh, en-us; q=0.8, en; q=0.6');
        $request->cookies->set('Foo', 'Bar');

        $asString = (string) $request;

        $this->assertContains('Accept-Language: zh, en-us; q=0.8, en; q=0.6', $asString);
        $this->assertContains('Cookie: Foo=Bar', $asString);

        $request->cookies->set('Another', 'Cookie');

        $asString = (string) $request;

        $this->assertContains('Cookie: Foo=Bar; Another=Cookie', $asString);
    }

    public function testIsMethod()
    {
        $request = new Request();
        $request->setMethod('POST');
        $this->assertTrue($request->isMethod('POST'));
        $this->assertTrue($request->isMethod('post'));
        $this->assertFalse($request->isMethod('GET'));
        $this->assertFalse($request->isMethod('get'));

        $request->setMethod('GET');
        $this->assertTrue($request->isMethod('GET'));
        $this->assertTrue($request->isMethod('get'));
        $this->assertFalse($request->isMethod('POST'));
        $this->assertFalse($request->isMethod('post'));
    }

    /**
     * @dataProvider getBaseUrlData
     */
    public function testGetBaseUrl($uri, $server, $expectedBaseUrl, $expectedPathInfo)
    {
        $request = Request::create($uri, 'GET', [], [], [], $server);

        $this->assertSame($expectedBaseUrl, $request->getBaseUrl(), 'baseUrl');
        $this->assertSame($expectedPathInfo, $request->getPathInfo(), 'pathInfo');
    }

    public function getBaseUrlData()
    {
        return [
            [
                '/fruit/strawberry/1234index.php/blah',
                [
                    'SCRIPT_FILENAME' => 'E:/Sites/cc-new/public_html/fruit/index.php',
                    'SCRIPT_NAME' => '/fruit/index.php',
                    'PHP_SELF' => '/fruit/index.php',
                ],
                '/fruit',
                '/strawberry/1234index.php/blah',
            ],
            [
                '/fruit/strawberry/1234index.php/blah',
                [
                    