}

    public function testSettingCharsetClearsCache()
    {
        $headers = $this->createHeaderSet([], false);
        $headers->shouldReceive('toString')
                ->zeroOrMoreTimes()
                ->andReturn("Content-Type: text/plain; charset=utf-8\r\n");

        $cache = $this->createCache(false);

        $entity = $this->createEntity($headers, $this->createEncoder(),
            $cache
            );

        $entity->setBody("blah\r\nblah!");
        $entity->toString();

        // Initialize the expectation here because we only care about what happens in setCharset()
        $cache->shouldReceive('clearKey')
                ->once()
                ->with(\Mockery::any(), 'body');

        $entity->setCharset('iso-2022');
    }

    public function testFormatIsReturnedFromHeader()
    {
        /* -- RFC 3676.
     */

        $cType = $this->createHeader('Content-Type', 'text/plain',
            ['format' => 'flowed']
            );
        $part = $this->createMimePart($this->createHeaderSet([
            'Content-Type' => $cType, ]),
            $this->createEncoder(), $this->createCache()
            );
        $this->assertEquals('flowed', $part->getFormat());
    }

    public function testFormatIsSetInHeader()
    {
        $cType = $this->createHeader('Content-Type', 'text/plain', [], false);
        $cType->shouldReceive('setParameter