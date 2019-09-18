his->assertTraceContains('fresh');
        $this->assertTraceNotContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertRegExp('/s-maxage=2/', $this->response->headers->get('Cache-Control'));
    }

    public function testDoesNotAssignDefaultTtlWhenResponseHasMustRevalidateDirective()
    {
        $this->setNextResponse(200, ['Cache-Control' => 'must-revalidate']);

        $this->cacheConfig['default_ttl'] = 10;
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('miss');
        $this->assertTraceNotContains('store');
        $this->assertNotRegExp('/s-maxage/', $this->response->headers->get('Cache-Control'));
        $this->assertEquals('Hello World', $this->response->getContent());
    }

    public function testFetchesFullResponseWhenCacheStaleAndNoValidatorsPresent()
    {
        $time = \DateTime::createFromFormat('U', time() + 5);
        $this->setNextResponse(200, ['Cache-Control' => 'public', 'Expires' => $time->format(DATE_RFC2822)]);

        // build initial request
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertNotNull($this->response->headers->get('Date'));
        $this->assertNotNull($this->response->headers->get('X-Content-Digest'));
        $this->assertNotNull($this->