      $this->assertIdenticalBinary(
            pack('C*', 0xD0, 0xB6, 0xD0, 0xBE), $stream->read(2)
            );
        $this->assertIdenticalBinary(pack('C*', 0xD0, 0xBB), $stream->read(1));
        $this->assertIdenticalBinary(
            pack('C*', 0xD1, 0x8E, 0xD0, 0xB1, 0xD1, 0x8B), $stream->read(3)
            );
        $this->assertIdenticalBinary(pack('C*', 0xD1, 0x85), $stream->read(1));

        $this->assertFalse($stream->read(1));
    }

    public function testCharactersCanBeReadAsByteArrays()
    {
        $reader = $this->getReader();
        $factory = $this->getFactory($reader);

        $stream = new Swift_CharacterStream_ArrayCharacterStream($factory, 'utf-8');

        $reader->shouldReceive('getInitialByteSize')
               ->zeroOrMoreTimes()
               ->andReturn(1);
        //String
        $reader->shouldReceive('validateByteSequence')->once()->with([0xD0], 1)->andReturn(1);
        $reader->shouldReceive('validateByteSequence')->once()->with([0xD0], 1)->andReturn(1);
        $reader->shouldReceive('validateByteSequence')->once()->with([0xD0], 1)->andReturn(1);
        //Stream
        $reader->shouldReceive('validateByteSequence')->once()->with([0xD0], 1)->andReturn(1);
        $reader->shouldReceive('validateByteSequence')->once()->with([0xD1], 1)->andReturn(1);
        $reader->shouldReceive('validateByteSequence')->once()->with([0xD0], 1)->andReturn(1);
        $reader->shouldReceive('validateByteSequence')->once()->with([0xD1], 1)->andReturn(1);
        $reader->shouldReceive('validateByteSequence')->once()->with([0xD1], 1)->andReturn(1);

        $stream->importString(pack('C*', 0xD0, 0x94, 0xD0, 0xB6, 0xD0, 0xBE));

        $stream->write(pack('C*',
            0xD0, 0xBB,
            0xD1, 0x8E,
            0xD0, 0xB1,
            0xD1, 0x8B,
            0xD1, 0x85
            )
        );

        $this->assertEquals([0xD0, 0x94], $stream->readBytes(1));
        $this->assertEquals([0xD0, 0xB6, 0xD0, 0xBE], $stream->readBytes(2));
        $this->assertEquals([0xD0, 0xBB], $stream->readBytes(1));
        $this->assertEquals(
            [0xD1, 0x8E, 0xD0, 0xB1, 0xD1, 0x8B], $stream->readBytes(3)
            );
        $this->assertEquals([0xD1, 0x85], $stream->readBytes(1));

        $this->assertFalse($stream->readBytes(1));
    }

    public function testRequestingLargeCharCountPastEndOfStream()
    {
        $reader = $this->getReader();
        $factory = $this->getFactory($reader);

        $stream = new Swift_CharacterStream_ArrayCharacterStream($factory, 'utf-8');

        $reader->shouldReceive('getInitialByteSize')
               ->zeroOrMoreTimes()
               ->andReturn(1);
        $reader->shouldReceive('validateByteSequence')->once()->with([0xD0], 1)->andReturn(1);
        $reader->shouldReceive('validateByteSequence')->once()->with([0xD0], 1)->andReturn(1);
        $reader->shouldReceive('validateByteSequence')->once()->with([0xD0], 1)->andReturn(1);

        $stream->importString(pack('C*', 0xD0, 0x94, 0xD0, 0xB6, 0xD0, 0xBE));

        $this->assertIdenticalBinary(pack('C*', 0xD0, 0x94, 0xD0, 0xB6, 0xD0, 0xBE),
            $stream->read(100)
            );

        $this->assertFalse($stream->read(1));
    }

    public function testRequestingByteArrayCountPastEndOfStream()
    {
        $reader = $this->getReader();
        $factory = $this->getFactory($reader);

        $stream = new Swift_CharacterStream_ArrayCharacterStream($factory, 'utf-8');

        $reader->shouldReceive('getInitialByteSize')
               ->zeroOrMoreTimes()
               ->andReturn(1);
        $reader->shouldReceive('validateByteSequence')->once()->with([0xD0], 1)->andReturn(1);
        $reader->shouldReceive('validateByteSequence')->once()->with([0xD0], 1)->andReturn(1);
        $reader->shouldReceive('validateByteSequence')->once()->with([0xD0], 1)->andReturn(1);

        $stream->importString(pack('C*', 0xD0, 0x94, 0xD0, 0xB6, 0xD0, 0xBE));

        $this->assertEquals([0xD0, 0x94, 0xD0, 0xB6, 0xD0, 0xBE],
            $stream->readBytes(100)
            );

        $this->assertFalse($stream->readBytes(1));
    }

    public function testPointerOffsetCanBeSet()
    {
        $reader = $this->getReader();
        $factory = $this->getFactory($reader);

        $stream = new Swift_CharacterStream_ArrayCharacterStream($factory, 'utf-8');

        $reader->shouldReceive('getInitialByteSize')
               ->zeroOrMoreTimes()
               ->andReturn(1);
        $reader->shouldReceive('validateByteSequence')->once()->with([0xD0], 1)->andReturn(1);
        $reader->shouldReceive('validateByteSequence')->once()->with([0xD0], 1)->andReturn(1);
        $reader->shouldReceive(