e($response->headers->hasCacheControlDirective('private'));

        $response->setCache(['public' => true]);
        $this->assertTrue($response->headers->hasCacheControlDirective('public'));
        $this->assertFalse($response->headers->hasCacheControlDirective('private'));

        $response->setCache(['public' => false]);
        $this->assertFalse($response->headers->hasCacheControlDirective('public'));
        $this->assertTrue($response->headers->hasCacheControlDirective('private'));

        $response->setCache(['private' => true]);
        $this->assertFalse($response->headers->hasCacheControlDirective('public'));
        $this->assertTrue($response->headers->hasCacheControlDirective('private'));

        $response->setCache(['private' => false]);
        $this->assertTrue($response->headers->hasCacheControlDirective('public'));
        $this->assertFalse($response->headers->hasCacheControlDirective('private'));

        $response->setCache(['immutable' => true]);
        $this->assertTrue($response->headers->hasCacheControlDirective('immutable'));

        $response->setCache(['immutable' => false]);
        $this->assertFalse($response->headers->hasCacheControlDirective('immutable'));
    }

    public function testSendContent()
    {
        $response = new Response('test response rendering', 200);

        ob_start();
        $response->sendContent();
        $string = ob_get_clean();
        $this->assertContains('test response rendering', $string);
    }

    public function testSetPublic()
    {
        $response = new Response();
        $response->setPublic();

        $this->assertTrue($response->headers->hasCacheControlDirective('public'));
        $this->assertFalse($response->headers->hasCacheControlDirective('private'));
    }

    public function testSetImmutable()
    {
        $response = new Response();
        $response->setImmutable();

        $this->assertTrue($response->headers->hasCacheControlDirective('immutable'));
    }

    public function testIsImmutable()
    {
        $response = new Response();
        $response->setImmutable();

        $this->assertTrue($response->isImmutable());
    }

    public function testSetDate()
    {
        $response = new Response();
        $response->setDate(\DateTime::createFromFormat(\DateTime::ATOM, '2013-01-26T09:21:56+0100', new \DateTimeZone('Europe/Berlin')));

        $this->assertEquals('2013-01-26T08:21:56+00:00', $response->getDate()->format(\DateTime::ATOM));
    }

    public function testSetDateWithImmutable()
    {
        $response = new Response();
        $response->setDate(\DateTimeImmutable::createFromFormat(\DateTime::ATOM, '2013-01-26T09:21:56+0100', new \DateTimeZone('Europe/Berlin')));

        $this->assertEquals('2013-01-26T08:21:56+00:00', $response->getDate()->format(\DateTime::ATOM));
    }

    public function testSetExpires()
    {
        $response = new Response();
        $response->setExpires(null);

        $this->assertNull($response->getExpires(), '->setExpires() remove the header when passed null');

        $now = $this->createDateTimeNow();
        $response->setExpires($now);

        $this->assertEquals($response->getExpires()->getTimestamp(), $now->getTimestamp());
    }

    public function testSetExpiresWithImmutable()
    {
        $response = new Response();

        $now = $this->createDateTimeImmutableNow();
        $response->setExpires($now);

        $this->assertEquals($response->getExpires()->getTimestamp(), $now->getTimestamp());
    }

    public function testSetLastModified()
    {
        $response = new Response();
        $response->setLastModified($this->createDateTimeNow());
        $this->assertNotNull($response->getLastModified());

        $response->setLastModified(null);
        $this->assertNull($response->getLastModified());
    }

    public function testSetLastModifiedWithImmutable()
    {
        $response = new Response();
        $response->setLastModified($this->createDateTimeImmutableNow());
        $this->assertNotNull($response->getLastModified());

        $response->setLastModified(null);
        $this->assertNull($response->getLastModified());
    }

    public function testIsInvalid()
    {
        $response = new Response();

        try {
            $response->setStatusCode(99);
            $this->fail();
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue($response->isInvalid());
        }

        try {
            $response->setStatusCode(650);
            $this->fail();
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue($response->isInvalid());
        }

        $response = new Response('', 200);
        $this->assertFalse($response->isInvalid());
    }

    /**
     * @dataProvider getStatusCodeFixtures
     */
    public function testSetStatusCode($code, $text, $expectedText)
    {
        $response = new Response();

        $response->setStatusCode($code, $text);

        $statusText = new \ReflectionProperty($response, 'statusText');
        $statusText->setAccessible(true);

        $this->assertEquals($expectedText, $statusText->getValue($response));
    }

    public function getStatusCodeFixtures()
    {
        return [
            ['200', null, 'OK'],
            ['200', false, ''],
            ['200', 'foo', 'foo'],
            ['199', null, 'unknown status'],
            ['199', false, ''],
            ['199', 'foo', 'foo'],
        ];
    }

    public function testIsInformational()
    {
        $response = new Response('', 100);
        $this->assertTrue($response->isInformational());

        $response = new Response('', 200);
        $this->assertFalse($response->isInformational());
    }

    public function testIsRedirectRedirection()
    {
        foreach ([301, 302, 303, 307] as $code) {
            $response = new Response('', $code);
            $this->assertTrue($response->isRedirection());
            $this->assertTrue($response->isRedirect());
        }

        $response = new Response('', 304);
        $this->assertTrue($response->isRedirection());
        $this->assertFalse($response->isRedirect());

        $response = new Response('', 200);
        $this->assertFalse($response->isRedirection());
        $this->assertFalse($response->isRedirect());

        $response = new Response('', 404);
        $this->assertFalse($response->isRedirection());
        $this->assertFalse($response->isRedirect());

        $response = new Response('', 301, ['Location' => '/good-uri']);
        $this->assertFalse($response->isRedirect('/bad-uri'));
        $this->assertTrue($response->isRedirect('/good-uri'));
    }

    public function testIsNotFound()
    {
        $response = new Response('', 404);
        $this->assertTrue($response->isNotFound());

        $response = new Response('', 200);
        $this->assertFalse($response->isNotFound());
    }

    public function testIsEmpty()
    {
        foreach ([204, 304] as $code) {
            $response = new Response('', $code);
            $this->assertTrue($response->isEmpty());
        }

        $response = new Response('', 200);
        $this->assertFalse($response->isEmpty());
    }

    public function testIsForbidden()
    {
        $response = new Response('', 403);
        $this->assertTrue($response->isForbidden());

        $response = new Response('', 200);
        $this->assertFalse($response->isForbidden());
    }

    public function testIsOk()
    {
        $response = new Response('', 200);
        $this->assertTrue($response->isOk());

        $response = new Response('', 404);
        $this->assertFalse($response->isOk());
    }

    public function testIsServerOrClientError()
    {
        $response = new Response('', 404);
        $this->assertTrue($response->isClientError());
        $this->assertFalse($response->isServerError());

        $response = new Response('', 500);
        $this->assertFalse($response->isClientError());
        $this->assertTrue($response->isServerError());
    }

    public function testHasVary()
    {
        $response = new Response();
        $this->assertFalse($response->hasVary());

        $response->setVary('User-Agent');
        $this->assertTrue($response->hasVary());
    }

    public function testSetEtag()
    {
        $response = new Response('', 200, ['ETag' => '"12345"']);
        $response->setEtag();

        $this->assertNull($response->headers->get('Etag'), '->setEtag() removes Etags when call with null');
    }

    /**
     * @dataProvider validContentProvider
     */
    public function testSetContent($content)
    {
        $response = new Response();
        $response->setContent($content);
        $this->assertEquals((string) $content, $response->getContent());
    }

    /**
     * @expectedException \UnexpectedValueException
     * @dataProvider invalidContentProvider
     */
    public function testSetContentInvalid($content)
    {
        $response = new Response();
        $response->setContent($content);
    }

    public function testSettersAreChainable()
    {
        $response = new Response();

        $setters = [
            'setProtocolVersion' => '1.0',
            'setCharset' => 'UTF-8',
            'setPublic' => null,
            'setPrivate' => null,
            'setDate' => $this->createDateTimeNow(),
            'expire' => null,
            'setMaxAge' => 1,
            'setSharedMaxAge' => 1,
            'setTtl' => 1,
            'setClientTtl' => 1,
        ];

        foreach ($setters as $setter => $arg) {
            $this->assertEquals($response, $response->{$setter}($arg));
        }
    }

    public function testNoDeprecationsAreTriggered()
    {
        new DefaultResponse();
        $this->getMockBuilder(Response::class)->getMock();

        // we just need to ensure that subclasses of Response can be created without any deprecations
        // being triggered if the subclass does not override any final methods
        $this->addToAssertionCount(1);
    }

    public function validContentProvider()
    {
        return [
            'obj' => [new StringableObject()],
            'string' => ['Foo'],
            'int' => [2],
        ];
    }

    public function invalidContentProvider()
    {
        return [
            'obj' => [new \stdClass()],
            'array' => [[]],
            'bool' => [true, '1'],
        ];
    }

    protected function createDateTimeOneHourAgo()
    {
        return $this->createDateTimeNow()->sub(new \DateInterval('PT1H'));
    }

    protected function createDateTimeOneHourLater()
    {
        return $this->createDateTimeNow()->add(new \DateInterval('PT1H'));
    }

    protected function createDateTimeNow()
    {
        $date = new \DateTime();

        return $date->setTimestamp(time());
    }

    protected function createDateTimeImmutableNow()
    {
        $date = new \DateTimeImmutable();

        return $date->setTimestamp(time());
    }

    protected function provideResponse()
    {
        return new Response();
    }

    /**
     * @see       http://github.com/zendframework/zend-diactoros for the canonical source repository
     *
     * @author    Fábio Pacheco
     * @copyright Copyright (c) 2015-2016 Zend Technologies USA Inc. (http://www.zend.com)
     * @license   https://github.com/zendframework/zend-diactoros/blob/master/LICENSE.md New BSD License
     */
    public function ianaCodesReasonPhrasesProvider()
    {
        if (!\in_array('https', stream_get_wrappers(), true)) {
            $this->markTestSkipped('The "https" wrapper is not available');
        }

        $ianaHttpStatusCodes = new \DOMDocument();

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 30,
                'user_agent' => __METHOD__,
            ],
        ]);

        $ianaHttpStatusCodes->loadXML(file_get_contents('https://www.iana.org/assignments/http-status-codes/http-status-codes.xml', false, $context));
        if (!$ianaHttpStatusCodes->relaxNGValidate(__DIR__.'/schema/http-status-codes.rng')) {
            self::fail('Invalid IANA\'s HTTP status code list.');
        }

        $ianaCodesReasonPhrases = [];

        $xpath = new \DOMXPath($ianaHttpStatusCodes);
        $xpath->registerNamespace('ns', 'http://www.iana.org/assignments');

        $records = $xpath->query('//ns:record');
        foreach ($records as $record) {
            $value = $xpath->query('.//ns:value', $record)->item(0)->nodeValue;
            $description = $xpath->query('.//ns:description', $record)->item(0)->nodeValue;

            if (\in_array($description, ['Unassigned', '(Unused)'], true)) {
                continue;
            }

            if (preg_match('/^([0-9]+)\s*\-\s*([0-9]+)$/', $value, $matches)) {
                for ($value = $matches[1]; $value <= $matches[2]; ++$value) {
                    $ianaCodesReasonPhrases[] = [$value, $description];
                }
            } else {
                $ianaCodesReasonPhrases[] = [$value, $description];
   