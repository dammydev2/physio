    $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTraceContains('fresh');
        $this->assertEquals(2, $this->response->headers->get('Age'));
    }

    public function testValidatesPrivateResponsesCachedOnTheClient()
    {
        $this->setNextResponse(200, [], '', function ($request, $response) {
            $etags = preg_split('/\s*,\s*/', $request->headers->get('IF_NONE_MATCH'));
            if ($request->cookies->has('authenticated')) {
                $response->headers->set('Cache-Control', 'private, no-store');
                $response->setETag('"private tag"');
                if (\in_array('"private tag"', $etags)) {
                    $response->setStatusCode(304);
                } else {
                    $response->setStatusCode(200);
                    $response->headers->set('Content-Type', 'text/plain');
                    $response->setContent('private data');
                }
            } else {
                $response->headers->set('Cache-Control', 'public');
                $response->setETag('"public tag"');
                if (\in_array('"public tag"', $etags)) {
      