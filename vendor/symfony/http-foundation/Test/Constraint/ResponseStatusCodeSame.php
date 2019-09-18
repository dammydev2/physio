       $this->assertObjectHasAttribute('headers', $modified);
        $this->assertObjectHasAttribute('content', $modified);
        $this->assertObjectHasAttribute('version', $modified);
        $this->assertObjectHasAttribute('statusCode', $modified);
        $this->assertObjectHasAttribute('statusText', $modified);
        $this->assertObjectHasAttribute('charset', $modified);
        $this->assertEquals(304, $modified->getStatusCode());

        ob_start();
        $modified->sendContent();
        $string = ob_get_clean();
        $this->assertEmpty($string);
    }

    public function testIsSuccessful()
    {
        $response = new Response();
        $this->assertTrue($response->isSuccessful());
    }

    public function testIsNotModified()
    {
        $response = new Response();
        $modified = $response->isNotModified(new Request());
        $this->assertFalse($modified);
    }

    public function testIsNotModifiedNotSafe()
    {
        $request = Request::create('/homepage', 'POST');

        $response = new Response();
        $this->assertFalse($response->isNotModified($request));
    }

    public function testIsNotModifiedLastModified()
    {
        $before = 'Sun, 25 Aug 2013 18:32:31 GMT';
        $modified = 'Sun, 25 Aug 2013 18:33:31 GMT';
        $after = 'Sun, 25 Aug 