sHttpOnly() returns whether the cookie is only transmitted over HTTP');
    }

    public function testCookieIsNotCleared()
    {
        $cookie = Cookie::create('foo', 'bar', time() + 3600 * 24);

        $this->assertFalse($cookie->isCleared(), '->isCleared() returns false if the cookie did not expire yet');
    }

    public function testCookieIsCleared()
    {
        $cookie = Cookie::create('foo', 'bar', time() - 20);

        $this->assertTrue($cookie->isCleared(), '->isCleared() returns true if the cookie has expired');

        $cookie = Cookie::create('foo', 'bar');

        $this->assertFalse($cookie->isCleared());

        $cookie = Cookie::create('foo', 'bar');

        $this->assertFalse($cookie->isCleared());

        $cookie = Cookie::create('foo', 'bar', -1);

        $this->assertFalse($cookie->isCleared());
    }

    public function testToString()
    {
        $cookie = Cookie::create('foo', 'bar', $expire = strtotime('Fri, 20-May-2011 15:25:52 GMT'), '/', '.myfoodomain.com', true, true, false, null);
        $this->assertEquals('foo=bar; expires=Fri, 20-May-2011 15:25:52 GMT; Max-Age=0; path=/; domain=.myfoodomain.com; secure; httponly', (string) $cookie, '->__toString() returns string representation of the cookie');

        $cookie = Cookie::create('foo', 'bar with white spaces', strtotime('Fri, 20-May-2011 15:25:52 GMT'), '/', '.myfoodomain.com', true, true, false, null);
        $this->assertEquals('foo=bar%20with%20white%20spaces; expires=Fri, 20-May-2011 15:25:52 GMT; Max-Age=0; path=/; domain=.myfoodomain.com; secure; httponly', (string) $cookie, '->__toString() encodes th