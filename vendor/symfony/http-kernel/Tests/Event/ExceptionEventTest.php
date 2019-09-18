sSetFalseDefault()
    {
        $count = 0;

        $this->setNextResponse(200, ['Cache-Control' => 'public, max-age=10000'], '', function ($request, $response) use (&$count) {
            ++$count;
            $response->setContent(1 == $count ? 'Hello World' : 'Goodbye World');
        });

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('store');

        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Hello World', $this->response->getContent());
        $this->assertTraceContains('fresh');

        $this->cacheCo