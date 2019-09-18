->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceNotContains('stale');
        $this->assertTraceNotContains('invalid');
        $this->assertTraceContains('fresh');
    }

    public function testFetchesResponseFromBackendWhenCacheMisses()
    {
        $time = \DateTime::createFromFormat('U', time() + 5);
        $this->setNextResponse(200, ['Cache-Control' => 'public', 'Expires' => $time->format(DATE_RFC2822)]);

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('miss');
        $this->assertTrue($this->response->headers->has('Age'));
    }

    public function testDoesNotCacheSomeStatusCodeResponses()
 