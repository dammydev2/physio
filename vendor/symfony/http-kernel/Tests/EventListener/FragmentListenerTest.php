eValidation()
    {
        $responses = [
            [
                'status' => 200,
                'body' => '<esi:include src="/foo" /> <esi:include src="/bar" />',
                'headers' => [
                    'Cache-Control' => 's-maxage=300',
                    'Surrogate-Control' => 'content="ESI/1.0"',
                ],
            ],
            [
                'status' => 200,
                'body' => 'Hello World!',
                'headers' => ['ETag' => 'foobar'],
            ],
            [
                'status' => 200,
                'body' => 'My name is Bobby.',
                'headers' => ['Cache-Control' => 's-maxage=100'],
            ],
        ];

        $this->setNextResponses($responses);

        $this->request('GET', '/', [], [], true);
        $this->assertEquals('Hello World! My name is Bobby.', $this->response->getContent());
        $this->assertNull($this->response->getTtl());
        $this->assertTrue($this->response->mustRevalidate());
        $this->assertTrue($this->response->headers->hasCacheControlDirective('private'));
        $this->assertTrue($this->response->headers->hasCacheControlDirective('no-cache'));
    }

    public function testEsiCacheForceValidationForHeadRequests()
    {
        $responses = [
            [
                'status' => 200,
                'body' => 'I am the master response and use expiration caching, but I embed another resource: <esi:include src="/foo" />',
                'headers' => [
                    'Cache-Control' => 's-maxage=300',
                    'Surrogate-Control' => 'content="ESI/1.0"',
                ],
            ],
            [
                'status' => 200,
                'body' => 'I am the embedded resource and use validation caching',
                'headers' => ['ETag' => 'foobar'],
            ],
        ];

        $this->setNextResponses($responses);

        $this->request('HEAD', '/', [], [], true);

        // The response has been assembled from expiration and validation based resources
        // This can neither be cached nor revalidated, so it should be private/no cache
        $this->assertEmpty($this->response->getContent());
        $this->assertNull($this->response->getTtl());
        $this->assertTrue($this->response->mustRevalidate());
        $this->assertTrue($this->response->headers->hasCacheControlDirective('private'));
        $this->assertTrue($this->response->headers->hasCacheControlDirective('no-cache'));
    }

    public function testEsiRecalculateContentLengthHeader()
    {
        $responses = [
            [
                'status' => 200,
                'body' => '<esi:include src="/foo" />',
                'headers' => [
                    'Content-Length' => 26,
                    'Surrogate-Control' => 'content="ESI/1.0"',
                ],
            ],
            [
                'status' => 200,
                'body' => 'Hello World!',
                'headers' => [],
            ],
        ];

        $this->setNextResponses($responses);

        $this->request('GET', '/', [], [], true);
        $this->assertEquals('Hello World!', $this->response->getContent());
        $this->assertEquals(12, $this->response->headers->get('Content-Length'));
    }

    public function testEsiRecalculateContentLengthHeaderForHeadRequest()
    {
        $responses = [
            [
                'status' => 200,
                'body' => '<esi:include src="/foo" />',
                'headers' => [
                    'Content-Length' => 26,
                    'Surrogate-Control' => 'content="ESI/1.0"',
                ],
            ],
            [
                'status' => 200,
                'body' => 'Hello World!',
                'headers' => [],
            ],
        ];

        $this->setNextResponses($responses);

        $this->request('HEAD', '/', [], [], true);

        // https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.13
        // "The Content-Length entity-header field indicates the size of the entity-body,
        // in decimal number of OCTETs, sent to the recipient or, in the case of the HEAD
        // method, the size of the entity-body that would have been sent had the request
        // been a GET."
        $this->assertEmpty($this->response->getContent());
        $this->assertEquals(12, $this->response->headers-