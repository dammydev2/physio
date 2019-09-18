->createEncoder(),
            $cache
            );

        $entity->setBody("blah\r\nblah!");
        $this->assertEquals(
            "Content-Type: text/plain; charset=utf-8\r\n".
            "\r\n".
            "cache\r\ncache!",
            $entity->toString()
            );
    }

    public function testBodyIsAddedToCacheWhenUsingToString()
    {
        $headers = $this->createHeaderSet([], false);
        $headers->shouldReceive('toString')
                ->zeroOrMoreTimes()
                ->andReturn("Content-Type: text/plain; charset=utf-8\r\n");

        $cache = $this->createCache(false);
        $cache->shouldReceive('hasKey')
              ->once()
              ->with(\Mockery::any(), 'body')
              ->andReturn(false);
        $cache->shouldReceive('setString')
              ->once()
              ->with(\Mockery::any(), 'body', "\r\nblah\r\nblah!", Swift_KeyCache::MODE_WRITE);

        $entity = $this->createEntity($headers, $this->createEncoder(),
            $cache
            );

        $entity->setBody("blah\r\nblah!");
        $entity->toString();
    }

    public function testBodyIsClearedFromCacheIfNewBodySet()
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

        // We set the expec