      $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertNotNull($this->response->headers->get('Date'));
        $this->assertNotNull($this->response->headers->get('X-Content-Digest'));
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');

        $values = $this->getMetaStorageValues();
        $this->assertCount(1, $values);
    }

    public function testCachesResponsesWithALastModifiedValidatorButNoFreshnessInformation()
    {
        $time = \DateTime::createFromFormat('U', time());
        $this->setNextResponse(200, ['Cache-Control' => 'public', 'Last-Modified' => $time->format(DATE_RFC2822)]);

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
    }

    public function testCachesResponsesWithAnETagValidatorButNoFreshnessInformation()
    {
        $this->setNextResponse(200, ['Cache-Control' => 'public', 'ETag' => '"123456"']);

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
    }

    public function testHitsCachedResponsesWithExpiresHeader()
    {
        $time1 = \DateTime::createFromFormat('U', time() - 5);
        $time2 = \DateTime::createFromFormat('U', time() + 5);
        $this->setNextResponse(200, ['Cache-Control' => 'public', 'Date' => $time1->format(DATE_RFC2822), 'Expires' => $time2->format(DATE_RFC2822)]);

        $this->request('GET', '/');
        $this->assertHttpKernelIsCalled();
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertNotNull($this->response->headers->get('Date'));
        $this->assertTraceContains('miss');
        $this->assertTraceContains('store');
        $this->assertEquals('Hello World', $this->response->getContent());

        $this->request('GET',