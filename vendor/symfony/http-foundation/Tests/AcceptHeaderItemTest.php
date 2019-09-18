s->set('ETag', $etag);
        $this->assertTrue($response->isNotModified($request));

        $response->headers->set('ETag', 'non-existent-etag');
        $this->assertFalse($response->isNotModified($request));
    }

    public function testIsValidateable()
    {
        $response = new Response('', 200, ['Last-Modified' => $this->createDateTimeOneHourAgo()->format(DATE_RFC2822)]);
        $this->assertTrue($response->isValidateable(), '->isValidateable() returns true if Last-Modified is present');

        $response = new Response('', 200, ['ETag' => '"12345"']);
        $this->assertTrue($response->isValidateable(), '->isValidateable() returns true if ETag is present');

        $response = new Response();
        $this->assertFalse($response->isValidateable(), '->isValidateable() returns false when no validator is present');
    }

    public function testGetDate()
    {
        $oneHourAgo = $this->createDateTimeOneHourAgo();
        $response = new Response('', 200, ['Date' => $oneHourAgo->format(DATE_RFC2822)]);
        $date = $response->getDate();
        $this->assertEquals($oneHourAgo->getTimestamp(), $date->getTimestamp(), '->getDate() returns the Date header if present');

        $response = new Response();
        $date = $response->getDate();
        $this->assertEquals(time(), $date->getTimestamp(), '->getDate() returns the current Date if no Date header present');

        $response = new Response('', 200, ['Date' => $this->createDateTimeOneHourAgo()->format(DATE_RFC2822)]);
        $now = $this->createDateTimeNow();
        $response->headers->set('Date', $now->format(DATE_RFC2822));
        $date = $response->getDate();
        $this->assertEquals($now->getTimestamp(), $date->getTimestamp(), '->getDate() returns the date when the header has been modified');

        $response = new Response('', 200);
        $now = $this->createDateTimeNow();
        $response->headers->remove('Date');
        $date = $response->getDate();
        $this->assertEquals($now->getTimestamp(), $date->getTimestamp(), '->getDate() returns the current Date when the header has previously been removed');
    }

    public function testGetMaxAge()
    {
        $response = new Response();
        $response->headers->set('Cache-Control', 's-maxage=600, max-age=0');
        $this->assertEquals(600, $response->getMaxAge(), '->getMaxAge() uses s-maxage cache control directive when present');

        $response = new Response();
        $response->headers->set('Cache-Control', 'max-age=600');
        $this->assertEquals(600, $response->getMaxAge(), '->getMaxAge() falls back to max-age when no s-maxage directive present');

        $response = new Response();
        $response->headers->set('Cache-Control', 'must-revalidate');
        $response->headers->set('Expires', $this->createDateTimeOneHourLater()->format(DATE_RFC2822));
        $this->assertEquals(3600, $response->getMaxAge(), '->getMaxAge() falls back to Expires when no max-age or s-maxage directive present');

        $response = new Response();
        $response->headers->set('Cache-Control', 'must-revalidate');
        $response->headers->set('Expires', -1);
        $this->assertLessThanOrEqual(time() - 2 * 86400, $response->getExpires()->format('U'));

        $response = new Response();
        $this->assertNull($response->getMaxAge(), '->getMaxAge() returns null if no freshness information available');
    }

    public function testSetSharedMaxAge()
    {
        $response 