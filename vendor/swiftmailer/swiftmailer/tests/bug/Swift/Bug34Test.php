dedToCacheWhenUsingToByteStream()
    {
        $is = $this->createInputStream();
        $cache = $this->createCache(false);
        $cache->shouldReceive('hasKey')
              ->once()
              ->with(\Mockery::any(), 'body')
              ->andReturn(false);
        $cache->shouldReceive('getInputByteStream')
              ->once()
              ->with(\Mockery::any(), 'body');

        $entity = $this->createEntity($this->createHeaderSet(),
            $this->createEncoder(), $cache
            );
        $entity->setBody('foo');

        $entity->toByteStream($is);
    }

    public function testFluidInterface()
    {
        $entity = $this->createEntity($this->createHeaderSet(),
            $this->createEncoder(), $this->createCache()
            );

        $this->assertSame($entity,
            $entity
            ->setContentType('text/plain')
            ->setEncoder($this->createEncoder())
            ->setId('foo@bar')
            ->setDescription('my description')
            ->setMaxLineLength(998)
            ->setBody('xx')
            ->setBoundary('xyz')
            ->setChildren([])
            );
    }

    abstract protected function createEntity($headers, $encoder, $cache);

    protected function createChild($level = null, $string = '', $stub = true)
    {
        $child = $this->getMockery('Swift_Mime_SimpleMimeEntity')->shouldIgnoreMissing();
        if (isset($level)) {
            $child->shouldReceive('getNestingLevel')
                  ->zeroOrMoreTimes()
                  ->andReturn($level);
        }
        $child->shouldReceive('toString')
              ->zeroOrMoreTimes()
              ->andReturn($string);

        return $child;
    }

    protected function createEncoder($name = 'quoted-printable', $stub = true)
    {
        $encoder = $this->getMockBuilder('Swift_Mime_ContentEncoder')->getMock();
        $encoder->expects($this->any())
                ->method('getName')
                ->will($this->returnValue($name));
        $encoder->expects($this->any())
                ->method('encodeString')
                ->will($this->returnCallback(function () {
                    $args = func_get_args();

                    return array_shift($args);
                }));

        return $encoder;
    }

    protected function createCache($stub = true)
    {
        return $this->getMockery('Swift_KeyCache')->shouldIgnoreMissing();
    }

    protected function createHeaderSet($headers = [], $stub =