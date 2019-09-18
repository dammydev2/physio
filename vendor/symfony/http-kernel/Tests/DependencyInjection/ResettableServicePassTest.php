        $this->assertTraceNotContains('store');
        $this->assertFalse($this->response->headers->has('Age'));
    }

    public function testDoesNotCacheRequestsWithACookieHeader()
    {
        $this->setNextResponse(200);
        $this->request('GET', '/', [], ['foo' => 'bar']);

        $this->assertHttpKernelIsCalled();
        $this->assertResponseOk();
        $this->assertEquals('private', $this->response->headers->get('Cache-Control'));
        $this->assertTraceContains('miss');
        $this->assertTraceNotContains('store');
        $this->assertFalse($this->response->headers->has('Age'));
    }

    public function testRespondsWith304WhenIfModifiedSinceMatchesLastModified()
    {
        $time = \DateTime::createFromFormat('U', time());

        $this->setNextResponse(200, ['Cache-Control' => 'public', 'Last-Modified' => $time->format(DATE_RFC2822), 'Content-Type' => 'text/plain'], 'Hello World');
        $this->request('GET', '/', ['HTTP_IF_MODIFIED_SINCE' => $time->format(DATE_RFC2822)]);

        $this->assertHttpKernelIsCalled();
        $this->assertEquals(304, $this->response->getStatusCode());
        $this->assertEquals('', $this->response->headers->get('Content-Type'));
        $this->assertEmpty($this->response->getContent());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
    }

    public function testRespondsWith304WhenIfNoneMatchMatchesETag()
    {
        $this->setNextResponse(200, ['Cache-Control' => 'public', 'ETag' => '12345', 'Content-Type' => 'text/plain'], 'Hello World');
        $this->request('GET', '/', ['HTTP_IF_NONE_MATCH' => '12345']);

        $this->assertHttpKernelIsCalled();
        $this->assertEquals(304, $this->response->getStatusCode());
        $this->assertEquals('', $this->response->headers->get('Content-Type'));
        $this->assertTrue($this->response->headers->has('ETag'));
        $this->assertEmpty($this->response->getContent());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
    }

    public function testRespondsWith304OnlyIfIfNoneMatchAndIfModifiedSinceBothMatch()
    {
        $time = \DateTime::createFromFormat('U', time());

        $this->setNextResponse(200, [], '', function ($request, $response) use ($time) {
            $response->setStatusCode(200);
            $response->headers->set('ETag', '12345');
            $response->headers->set('Last-Modified', $time->format(DATE_RFC2822));
            $response->headers->set('Content-Type', 'text/plain');
            $response->setContent('Hello World');
        });

        // only ETag matches
        $t = \DateTime::createFromFormat('U', time() - 3600);
        $this->request('GET', '/', ['HTTP_IF_NONE_MATCH' => '12345', 'HTTP_IF_MODIFIED_SINCE' => $t->format(DATE_RFC2822)]);
        $this->assertHttpKernelIsCalled();
        $this->assertEq