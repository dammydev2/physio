->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('stale');
        $this->assertTraceContains('valid');
    }

    public function testReplacesCachedResponsesWhenValidationResultsInNon304Response()
    {
        $time = \DateTime::createFromFormat('U', time());
        $count = 0;
        $this->setNextResponse(200, [], 'Hello World', function ($request, $response) use ($time, &$count) {
            $response->headers->set('Last-Modified', $time->format(DATE_RFC2822));
            $response->headers->set('Cache-Control', 'public');
            switch (++$count) {
                case 1:
                    $response->setContent('first response');
                    break;
                case 2:
                    $response->setContent('second response');
                    break;
                case 3:
                    $response->setContent('');
                    $response->setStatusCode(304);
                    break;
            }
        });

        // first request should fetch from backend and store in cache
        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('first response', $this->response->getContent());

        // second request is validated, is invalid, and replaces cached entry
        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('second response', $this->response->getContent());

        // third response is validated, valid, and returns cached entry
        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('second response', $this->response->getContent());

        $this->assertEquals(3, $count);
    }

    public function testPassesHeadRequestsThroughDirectlyOnPass()
    {
        $this->setNextResponse(200, [], 'Hello World', function ($request, $response) {
            $response->setContent('');
            $response->setStatusCode(200);
            $this->assertEquals('HEAD', $request->getMethod());
        });

        $this->request('HEAD', '/', ['HTTP_EXPECT' => 'something ...']);
        $this->assertHttpKernelIsCalled();
        $this->assertEquals('', $this->response->getContent());
    }

    public function testUsesCacheToRespondToHeadRequestsWhenFresh()
    {
        $this->setNextResponse(200, [], 'Hello World', function ($request, $response) {
            $response->headers->set('Cache-Control', 'public, max-age=10');
            $response->setContent('Hello World');
            $response->setStatusCode(200);
            $this->assertNotEquals('HEAD', $request->getMethod());
        });

        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals('Hello World', $this->response->getContent());

        $this->request('HEAD', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('', $this->response->getContent());
        $this->assertEquals(\strlen('Hello World'), $this->response->headers->get('Content-Length'));
    }

    public function testSendsNoContentWhenFresh()
    {
        $time = \DateTime::createFromFormat('U', time());
        $this->setNextResponse(200, [], 'Hello World', function ($request, $response) use ($time) {
            $response->headers->set('Cache-Control', 'public, max-age=10');
            $response->headers->set('Last-Modified', $time->format(DATE_RFC2822));
        });

        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals('Hello World', $this->response->getContent());

        $this->request('GET', '/', ['HTTP_IF_MODIFIED_SINCE' => $time->format(DATE_RFC2822)]);
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(304, $this->response->getStatusCode());
        $this->assertEquals('', $this->response->getContent());
    }

    public function testInvalidatesCachedResponsesOnPost()
    {
        $this->setNextResponse(200, [], 'Hello World', function ($request, $response) {
            if ('GET' == $request->getMethod()) {
                $response->setStatusCode(200);
                $response->headers->set('Cache-Control', 'public, max-age=500');
                $response->setContent('Hello World');
            } elseif ('POST' == $request->getMethod()) {
                $response->setStatusCode(303);
                $response->headers->set('Location', '/');
                $response->headers->remove('Cache-Control');
                $response->setContent('');
            }
        });

        // build initial request to enter into the cache
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');

        // make sure it is valid
        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('fresh');

        // now POST to same URL
        $this->request('POST', '/helloworld');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals('/', $this->response->headers->get('Location'));
        $this->assertTraceContains('invalidate');
        $this->assertTraceContains('pass');
        $this->assertEquals('', $this->response->getContent());

        // now make sure it was actually invalidated
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('stale');
        $this->assertTraceContains('invalid');
        $this->assertTraceContains('store');
    }

    public function testServesFromCacheWhenHeadersMatch()
    {
        $count = 0;
        $this->setNextResponse(200, ['Cache-Control' => 'max-age=10000'], '', function ($request, $response) use (&$count) {
            $response->headers->set('Vary', 'Accept User-Agent Foo');
            $response->headers->set('Cache-Control', 'public, max-age=10');
            $response->headers->set('X-Response-Count', ++$count);
            $response->setContent($request->headers->get('USER_AGENT'));
        });

        $this->request('GET', '/', ['HTTP_ACCEPT' => 'text/html', 'HTTP_USER_AGENT' => 'Bob/1.0']);
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Bob/1.0', $this->response->getContent());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');

        $this->request('GET', '/', ['HTTP_ACCEPT' => 'text/html', 'HTTP_USER_AGENT' => 'Bob/1.0']);
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Bob/1.0', $this->response->getContent());
  