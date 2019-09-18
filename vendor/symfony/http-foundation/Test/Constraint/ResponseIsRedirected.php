(ResponseHeaderBag::COOKIES_ARRAY);
        $this->assertArrayHasKey('/path/foo', $cookies['foo.bar']);

        $bag->removeCookie('foo', '/path/foo', 'foo.bar');
        $this->assertTrue($bag->has('set-cookie'));

        $cookies = $bag->getCookies(ResponseHeaderBag::COOKIES_ARRAY);
        $this->assertArrayNotHasKey('/path/foo', $cookies['foo.bar']);

        $bag->removeCookie('bar', '/path/bar', 'foo.bar');
        $this->assertFalse($bag->has('set-cookie'));

        $cookies = $bag->getCookies(ResponseHeaderBag::COOKIES_ARRAY);
        $this->assertArrayNotHasKey('foo.bar', $cookies);
    }

    public function testRemoveCookieWithNullRemove()
    {
        $bag = new ResponseHeaderBag();
        $bag->setCookie(Cookie::create('foo', 'bar'));
        $bag->setCookie(Cookie::create('bar', 'foo'));

        $cookies = $bag->getCookies(ResponseHeaderBag::COOKIES_ARRAY);
        $this->assertArrayHasKey('/', $cookies['']);

        $bag->removeCookie('foo', null);
        $cookies = $bag->getCookies(ResponseHeaderBag::COOKIES_ARRAY);
        $this->assertArrayNotHasKey('foo', $cookies['']['/']);

        $bag->removeCoo