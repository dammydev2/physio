)
                ->zeroOrMoreTimes();

        $entity = $this->createEntity($headers, $this->createEncoder(),
            $this->createCache()
            );
        $entity->setDescription('whatever');
    }

    public function testSetAndGetMaxLineLength()
    {
        $entity = $this->createEntity($this->createHeaderSet(),
            $this->createEncoder(), $this->createCache()
            );
        $entity->setMaxLineLength(60);
        $this->assertEquals(60, $entity->getMaxLineLength());
    }

    public function testEncoderIsUsedForStringGeneration()
    {
        $encoder = $this->createEncoder('base64', false);
        $encoder->expects($this->once())
                ->method('encodeString')
                ->with('blah');

        $entity = $this->createEntity($this->createHeaderSet(),
            $encoder, $this->createCache()
            );
        $entity->setBody('blah');
        $entity->toString();
    }

    public function testMaxLineLengthIsProvidedWhenEncoding()
    {
        $encoder = $this->createEncoder('base64', false);
        $encoder->expects($this->once())
                ->method('encodeString')
                ->with('blah', 0, 65);

        $entity = $this->createEntity($this->createHeaderSet(),
            $encoder, $this->createCache()
            );
        $entity->setBody('blah');
        $entity->setMaxLineLength(65);
        $entity->toString();
    }

    public function testHeadersAppearInString()
    {
        $headers = $this->createHeaderSet([], false);
        $headers->shouldReceive('toString')
                ->once()
                ->andReturn(
                    "Content-Type: text/plain; charset=utf-8\r\n".
                    "X-MyHeader: foobar\r\n"
                );

        $entity = $this->createEntity($headers, $this->createEncoder(),
            $this->createCache()
            );
        $this->assertEqual