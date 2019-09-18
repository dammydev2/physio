function testDegradationWhenCacheLocked()
    {
        if ('\\' === \DIRECTORY_SEPARATOR) {
            $this->markTestSkipped('Skips on windows to avoid permissions issues.');
        }

        $this->cacheConfig['stale_while_revalidate'] = 10;

        // The prescence of Last-Modified makes this cacheable (because Response::isValidateable() then).
        $this->setNextResponse(200, ['Cache-Control' => 'public, s-maxage=5', 'Last-Modified' => 'some while ago'], 'Old response');
        $this->request('GET', '/'); // warm the cache

        // Now, lock the cache
        $concurrentRequest = Request::create('/', 'GET');
        $this->store->lock($concurrentRequest);

        /*
         *  After 10s, the cached response has become stale. Yet, we're still within the "stale_while_revalidate"
         *  timeout so we may serve the stale response.
         */
        sleep(10);

        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('stale-while-revalidate');
        $this->assertEquals('Old response', $this->response->getContent());

        /*
         * Another 10s later, stale_while_revalidate is over. Resort to serving the old response, but
         * do so with a "server unavailable" message.
         */
        sleep(10);

        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(503, $this->response->getStatusCode());
        $this->assertEquals('Old response', $this->response->getContent());
    }

    public function testHitsCachedResponseWithSMaxAgeDirective()
    {
        $time = \DateTime::createFromFormat('U', time() - 5);
        $this->setNextResponse(200, ['Date' => $time->format(DATE_RFC2822), 'Cache-Control' => 's-maxage=10, max-age=0']);

        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertNotNull($this->response->headers->get('Date'));
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());

        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertLessThan(2, strtotime($this->responses[0]->headers->get('Date')) - strtotime($this->response->headers->get('Date')));
        $this->assertGreaterThan(0, $this->response->headers->get('Age'));
        $this->assertNotNull($this->response->headers->get('X-Content-Digest'));
        $this->assertTraceContains('fresh');
        $this->assertTraceNotContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
    }

    public function testAssignsDefaultTtlWhenResponseHasNoFreshnessInformation()
    {
        $this->setNextResponse();

        $this->cacheConfig['default_ttl'] = 10;
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertRegExp('/s-maxage=10/', $this->response->headers->get('Cache-Control'));

        $this->cacheConfig['default_ttl'] = 10;
        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('fresh');
        $this->assertTraceNotContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertRegExp('/s-maxage=10/', $this->response->headers->get('Cache-Control'));
    }

    public function testAssignsDefaultTtlWhenResponseHasNoFreshnessInformationAndAfterTtlWasExpired()
    {
        $this->setNextResponse();

        $this->cacheConfig['default_ttl'] = 2;
        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertRegExp('/s-maxage=2/', $this->response->headers->get('Cache-Control'));

        $this->request('GET', '/');
        $this->assertHttpKernelIsNotCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('fresh');
        $this->assertTraceNotContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertRegExp('/s-maxage=2/', $this->response->headers->get('Cache-Control'));

        // expires the cache
        $values = $this->getMetaStorageValues();
        $this->assertCount(1, $values);
        $tmp = unserialize($values[0]);
        $time = \DateTime::createFromFormat('U', time() - 5);
        $tmp[0][1]['date'] = $time->format(DATE_RFC2822);
        $r = new \ReflectionObject($this->s