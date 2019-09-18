ective('private'), '->isPrivate() adds the private Cache-Control directive when set to true');

        $response = new Response();
        $response->headers->set('Cache-Control', 'public, max-age=100');
        $response->setPrivate();
        $this->assertEquals(100, $response->headers->getCacheControlDirective('max-age'), '->isPrivate() adds the private Cache-Control directive when set to true');
        $this->assertTrue($response->headers->getCacheControlDirective('private'), '->isPrivate() adds the private Cache-Control directive when set to true');
        $this->assertFalse($response->headers->hasCacheControlDirective('public'), '->isPrivate() removes the public Cache-Control directive');
    }

    public function testExpire()
    {
        $response = new Response();
        $response->headers->set('Cache-Control', 'max-age=100');
        $response->expire();
        $this->assertEquals(100, $response->headers->get('Age'), '->expire() sets the Age to max-age when present');

        $response = new Response();
        $response->headers->set('Cache-Control', 'max-age=100, s-maxage=500');
        $response->expire();
        $this->assertEquals(500, $response->headers->get('Age'), '->expire() sets the Age to s-maxage when both max-age and s-maxage are present');

        $response = new Response();
        $response->headers->set('Cache-Control', 'max-age=5, s-maxage=500');
        $response->headers->set('Age', '1000');
        $response->expire();
        $this->assertEquals(1000, $response->headers->get('Age'), '->expire() does nothing when the response is already stale/expired');

        $response = new Response();
        $response->expire();
        $this->assertFalse($response->headers->has('Age'), '->expire() does nothing when the response does not include freshness information');

        $response = new Response();
        $response->headers->set('Expires', -1);
        $response->expire();
        $this->assertNull($response->headers->get('Age'), '->expire() does not set the Age when the response is expired');

        $response = new Response();
        $response->headers->set('Expires', date(DATE_RFC2822, time() + 600));
        $response->expire();
        $this->assertNull($response->headers->get('Expires'), '->expire() removes the Expires header when the response is fresh');
    }

    public function testGetTtl()
    {
        $response = new Response();
        $this->assertNull($response->getTtl(), '->getTtl() returns null when no Expires or Cache-Control headers are present');

        $response = new Response();
        $response->headers->set('Expires', $this->createDateTimeOneHourLater()->format(DATE_RFC2822));
        $this->assertEquals(3600, $response->getTtl(), '->getTtl() uses the Expires header when no max-age is present');

        $response = new Response();
        $response->headers->set('Expires', $this->createDateTimeOneHourAgo()->format(DATE_RFC2822));
        $this->assertLessThan(0, $response->getTtl(), '->getTtl() returns negative values when Expires is in past');

        $response = new Response();
        $response->headers->set('Expires', $response->getDate()->format(DATE_RFC2822));
        $response->headers->set('Age', 0);
        $this->assertSame(0, $response->getTtl(), '->getTtl() correctly handles zero');

        $response = new Response();
        $response->headers->set('Cache-Control', 'max-age=60');
        $this->assertEquals(60, $response->getTtl(), '->getTtl() uses Cache-Control max-age when present');
    }

    public function testSetClientTtl()
    {
        $response = new Response();
        $response->setClientTtl(10);

        $this->assertEquals($response->getMaxAge(), $response->getAge() + 10);
    }

    public function testGetSetProtocolVersion()
    {
        $response = new Response();

        $this->assertEquals('1.0', $response->getProtocolVersion());

        $response->setProtocolVersion('1.1');

        $this->assertEquals('1.1', $response->getProtocolVersion());
    }

    public function testGetVary()
    {
        $response = new Response();
        $this->assertEquals([], $response->getVary(), '->getVary() returns an empty array if no Vary header is present');

        $response = new Response();
        $response->headers->set('Vary', 'Accept-Language');
        $this->assertEquals(['Accept-Language'], $response->getVary(), '->getVary() parses a single header name value');

        $response = new Response();
        $response->headers->set('Vary', 'Accept-Language User-Agent    X-Foo');
        $this->assertEquals(['Accept-Language', 'User-Agent', 'X-Foo'], $response->getVary(), '->getVary() parses multiple header name values separated by spaces');

        $response = new Response();
        $response->headers->set('Vary', 'Accept-Language,User-Agent,    X-Foo');
        $t