request; should be found but miss due to freshness
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertNotNull($this->response->headers->get('Last-Modified'));
        $this->assertNotNull($this->response->headers->get('X-Content-Digest'));
        $this->assertLessThanOrEqual(1, $this->response->headers->get('Age'));
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('stale');
        $this->assertTraceContains('valid');
        $this->assertTraceContains('store');
        $this->assertTraceNotContains('miss');
    }

    public function testValidatesCachedResponsesUseSameHttpMethod()
    {
        $test = $this;

        $this->setNextResponse(200, [], 'Hello World', function ($request, $response) use ($test) {
            $test->assertSame('OPTIONS', $request->getMethod());
        });

        // build initial request
        $this->request('OPTIONS', '/');

        // build subsequent request
        $this->request('OPTIONS', '/');
    }

    public function testValidatesCachedResponsesWithETagAndNoFreshnessInformation()
    {
        $this->setNextResponse(200, [], 'Hello World', function ($request, $response) {
            $response->headers->set('Cache-Control', 'public');
            $response->headers->set('ETag', '"12345"');
            if ($response->getETag() == $request->headers->get('IF_NONE_MATCH')) {
                $response->setStatusCode(304);
                $response->setContent('');
            }
        });

        // build initial request
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertNotNull($this->response->headers->get('ETag'));
        $this->assertNotNull($this->respo