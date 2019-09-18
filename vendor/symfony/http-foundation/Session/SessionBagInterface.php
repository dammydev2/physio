data);
        $response = clone $response;
        $response->prepare($request);
        $response->sendContent();

        $this->assertEquals(206, $response->getStatusCode());
        $this->assertEquals($responseRange, $response->headers->get('Content-Range'));
        $this->assertSame($length, $response->headers->get('Content-Length'));
    }

    /**
     * @dataProvider provideRanges
     */
    public function testRequestsWithoutEtag($requestRange, $offset, $length, $responseRange)
    {
        $response = BinaryFileResponse::create(__DIR__.'/File/Fixtures/test.gif', 200, ['Content-Type' => 'application/octet-stream']);

        // do a request to get the LastModified
        $request = Request::create('/');
        $response->prepare($request);
        $lastModified = $response->headers->get('Last-Modified');

        // prepa