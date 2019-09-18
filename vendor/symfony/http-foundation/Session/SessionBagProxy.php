->sendContent();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @dataProvider provideInvalidRanges
     */
    public function testInvalidRequests($requestRange)
    {
        $response = BinaryFileResponse::create(__DIR__.'/File/Fixtures/test.gif', 200, ['Content-Type' => 'application/octet-stream'])->setAutoEtag();

        // prepare a request for a range of the testing file
        $request = Request::create('/');
        $request->headers->set('Range', $requestRange);

        $response = clone $response;
        $response->prepare($request);
        $response->sendContent();

        $this->assertEquals(416, $response->getStatusCode());
        $this->assertEquals('bytes */35', $response->headers->get('Content-Range'));
    }

    public function provideInvalidRanges()
    {
        return [
            ['bytes=-40'],
            ['bytes=30-40'],
        ];
    }

    /**
     * @dataProvider provideXSendfileFiles
     */
    public function testXSendfile($file)
    {
        $request = Request::create('/');
        $request->headers->set('X-Sendfile-Type', 'X-Sendfile');

        BinaryFileResponse::trustXSendfileTypeHeader();
        $response = BinaryFileResponse::create($file, 200, ['Content-Type' => 'application/octet-stream']);
        $response->prepare($request);

        $this->expectOutputString('');
        $response->sendContent();

        $this->assertContains('README.md', $response->headers->get('X-Sendfile'));
    }

    public function provideXSendfileFiles()
    {
        return [
            [__DIR__.'/../README.md'],
            ['file://'.__DIR__.'/../README.md'],
        ];
    }

    /**
     * @