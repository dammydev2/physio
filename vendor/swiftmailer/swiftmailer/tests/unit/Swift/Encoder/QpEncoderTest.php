such transfers) are
                    known to pad lines of text with SPACEs, and others are
                    known to remove "white space" characters from the end
                    of a line.  Therefore, when decoding a Quoted-Printable
                    body, any trailing white space on a line must be
                    deleted, as it will necessarily have been added by
                    intermediate transport agents.
                    */

        $HT = chr(0x09); //9
        $SPACE = chr(0x20); //32

        //HT
        $os = $this->createOutputByteStream(true);
        $charStream = $this->createCharacterStream();
        $is = $this->createInputByteStream();
        $collection = new Swift_StreamCollector();

        $is->shouldReceive('write')
               ->zeroOrMoreTimes()
               ->andReturnUsing($collection);
        $charStream->shouldReceive('flushContents')
                   ->once();
        $charStream->shouldReceive('importByteStream')
                   ->once()
                   ->with($os);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([ord('a')]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([0x09]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([0x09]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([0x0D]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([0x0A]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([ord('b')]);
        $charStream->shouldReceive('readBytes')
                   ->zeroOrMoreTimes()
                   ->andReturn(false);

        $encoder = new Swift_Mime_ContentEncoder_QpContentEncoder($charStream);
        $encoder->encodeByteStream($os, $is);

        $this->assertEquals("a\t=09\r\nb", $collection->content);

        //SPACE
        $os = $this->createOutputByteStream(true);
        $charStream = $this->createCharacterStream();
        $is = $this->createInputByteStream();
        $collection = new Swift_StreamCollector();

        $is->shouldReceive('write')
               ->zeroOrMoreTimes()
               ->andReturnUsing($collection);
        $charStream->shouldReceive('flushContents')
                   ->once();
        $charStream->shouldReceive('importByteStream')
                   ->once()
                   ->with($os);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([ord('a')]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([0x20]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([0x20]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([0x0D]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([0x0A]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([ord('b')]);
        $charStream->shouldReceive('readBytes')
                   ->zeroOrMoreTimes()
                   ->andReturn(false);

        $encoder = new Swift_Mime_ContentEncoder_QpContentEncoder($charStream);
        $encoder->encodeByteStream($os, $is);

        $this->assertEquals("a =20\r\nb", $collection->content);
    }

    public function testCRLFIsLeftAlone()
    {
        /*
        (4)   (Line Breaks) A line break in a text body, represented
                    as a CRLF sequence in the text canonical form, must be
                    represented by a (RFC 822) line break, which is also a
                    CRLF sequence, in the Quoted-Printable encoding.  Since
                    the canonical representation of media types other than
                    text do not generally include the representation of
                    line breaks as CRLF sequences, no hard line breaks
                    (i.e. line breaks that are intended to be meaningful
                    and to be displayed to the user) can occur in the
                    quoted-printable encoding of such types.  Sequences
                    like "=0D", "=0A", "=0A=0D" and "=0D=0A" will routinely
                    appear in non-text data represented in quoted-
                    printable, of course.

                    Note that many implementations may elect to encode the
                    local representation of various content types directly
                    rather than converting to canonical form first,
                    encoding, and then converting back to local
                    representation.  In particular, this may apply to plain
                    text material on systems that use newline conventions
                    other than a CRLF terminator sequence.  Such an
                    implementation optimization is permissible, but only
                    when the combined canonicalization-encoding step is
                    equivalent to performing the three steps separately.
                    */

        $os = $this->createOutputByteStream(true);
        $charStream = $this->createCharacterStream();
        $is = $this->createInputByteStream();
        $collection = new Swift_StreamCollector();

        $is->shouldReceive('write')
               ->zeroOrMoreTimes()
               ->andReturnUsing($collection);
        $charStream->shouldReceive('flushContents')
                   ->once();
        $charStream->shouldReceive('importByteStream')
                   ->once()
                   ->with($os);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([ord('a')]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([0x0D]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([0x0A]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([ord('b')]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([0x0D]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([0x0A]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([ord('c')]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([0x0D]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([0x0A]);
        $charStream->shouldReceive('readBytes')
                   ->zeroOrMoreTimes()
                   ->andReturn(false);

        $encoder = new Swift_Mime_ContentEncoder_QpContentEncoder($charStream);
        $encoder->encodeByteStream($os, $is);
        $this->assertEquals("a\r\nb\r\nc\r\n", $collection->content);
    }

    public function testLinesLongerThan76CharactersAreSoftBroken()
    {
        /*
        (5)   (Soft Line Breaks) The Quoted-Printable encoding
                    REQUIRES that encoded lines be no more than 76
                    characters long.  If longer lines are to be encoded
                    with the Quoted-Printable encoding, "soft" line breaks
                    must be used.  An equal sign as the last character on a
                    encoded line indicates such a non-significant ("soft")
                    line break in the encoded text.
                    */

        $os = $this->createOutputByteStream(true);
        $charStream = $this->createCharacterStream();
        $is = $this->createInputByteStream();
        $collection = new Swift_StreamCollector();

        $is->shouldReceive('write')
           ->zeroOrMoreTimes()
           ->andReturnUsing($collection);
        $charStream->shouldReceive('flushContents')
                   ->once();
        $charStream->shouldReceive('importByteStream')
                   ->once()
                   ->with($os);

        for ($seq = 0; $seq <= 140; ++$seq) {
            $charStream->shouldReceive('readBytes')
                       ->once()
                       ->andReturn([ord('a')]);
        }
        $charStream->shouldReceive('readBytes')
                   ->zeroOrMoreTimes()
                   ->andReturn(false);

        $encoder = new Swift_Mime_ContentEncoder_QpContentEncoder($charStream);
        $encoder->encodeByteStream($os, $is);
        $this->assertEquals(str_repeat('a', 75)."=\r\n".str_repeat('a', 66), $collection->content);
    }

    public function testMaxLineLengthCanBeSpecified()
    {
        $os = $this->createOutputByteStream(true);
        $charStream = $this->createCharacterStream();
        $is = $this->createInputByteStream();
        $collection = new Swift_StreamCollector();

        $is->shouldReceive('write')
           ->zeroOrMoreTimes()
           ->andReturnUsing($collection);
        $charStream->shouldReceive('flushContents')
                   ->once();
        $charStream->shouldReceive('importByteStream')
                   ->once()
                   ->with($os);

        for ($seq = 0; $seq <= 100; ++$seq) {
            $charStream->shouldReceive('readBytes')
                       ->once()
                       ->andReturn([ord('a')]);
        }
        $charStream->shouldReceive('readBytes')
                   ->zeroOrMoreTimes()
                   ->andReturn(false);

        $encoder = new Swift_Mime_ContentEncoder_QpContentEncoder($charStream);
        $encoder->encodeByteStream($os, $is, 0, 54);
        $this->assertEquals(str_repeat('a', 53)."=\r\n".str_repeat('a', 48), $collection->content);
    }

    public function testBytesBelowPermittedRangeAreEncoded()
    {
        /*
        According to Rule (1 & 2)
        */

        foreach (range(0, 32) as $ordinal) {
            $char = chr($ordinal);

            $os = $this->createOutputByteStream(true);
            $charStream = $this->createCharacterStream();
            $is = $this->createInputByteStream();
            $collection = new Swift_StreamCollector();

            $is->shouldReceive('write')
               ->zeroOrMoreTimes()
               ->andReturnUsing($collection);
            $charStream->shouldReceive('flushContents')
                       ->once();
            $charStream->shouldReceive('importByteStream')
                       ->once()
                       ->with($os);
            $charStream->shouldReceive('readBytes')
                       ->once()
                       ->andReturn([$ordinal]);
            $charStream->shouldReceive('readBytes')
                       ->zeroOrMoreTimes()
                       ->andReturn(false);

            $encoder = new Swift_Mime_ContentEncoder_QpContentEncoder($charStream);
            $encoder->encodeByteStream($os, $is);
            $this->assertEquals(sprintf('=%02X', $ordinal), $collection->content);
        }
    }

    public function testDecimalByte61IsEncoded()
    {
        /*
        According to Rule (1 & 2)
        */

        $char = chr(61);

        $os = $this->createOutputByteStream(true);
        $charStream = $this->createCharacterStream();
        $is = $this->createInputByteStream();
        $collection = new Swift_StreamCollector();

        $is->shouldReceive('write')
               ->zeroOrMoreTimes()
               ->andReturnUsing($collection);
        $charStream->shouldReceive('flushContents')
                       ->once();
        $charStream->shouldReceive('importByteStream')
                       ->once()
                       ->with($os);
        $charStream->shouldReceive('readBytes')
                       ->once()
                       ->andReturn([61]);
        $charStream->shouldReceive('readBytes')
                       ->zeroOrMoreTimes()
                       ->andReturn(false);

        $encoder = new Swift_Mime_ContentEncoder_QpContentEncoder($charStream);
        $encoder->encodeByteStream($os, $is);
        $this->assertEquals(sprintf('=%02X', 61), $collection->content);
    }

    public function testBytesAbovePermittedRangeAreEncoded()
    {
        /*
        According to Rule (1 & 2)
        */

        foreach (range(127, 255) as $ordinal) {
            $char = chr($ordinal);

            $os = $this->createOutputByteStream(true);
            $charStream = $this->createCharacterStream();
            $is = $this->createInputByteStream();
            $collection = new Swift_StreamCollector();

            $is->shouldReceive('write')
               ->zeroOrMoreTimes()
               ->andReturnUsing($collection);
            $charStream->shouldReceive('flushContents')
                       ->once();
            $charStream->shouldReceive('importByteStream')
                       ->once()
                       ->with($os);
            $charStream->shouldReceive('readBytes')
                       ->once()
                       ->andReturn([$ordinal]);
            $charStream->shouldReceive('readBytes')
                       ->zeroOrMoreTimes()
                       ->andReturn(false);

            $encoder = new Swift_Mime_ContentEncoder_QpContentEncoder($charStream);
            $encoder->encodeByteStream($os, $is);
            $this->assertEquals(sprintf('=%02X', $ordinal), $collection->content);
        }
    }

    public function testFirstLineLengthCanBeDifferent()
    {
        $os = $this->createOutputByteStream(true);
        $charStream = $this->createCharacterStream();
        $is = $this->createInputByteStream();
        $collection = new Swift_StreamCollector();

        $is->shouldReceive('write')
               ->zeroOrMoreTimes()
               ->andReturnUsing($collection);
        $charStream->shouldReceive('flushContents')
                    ->once();
        $charStream->shouldReceive('importByteStream')
                    ->once()
                    ->with($os);

        for ($seq = 0; $seq <= 140; ++$seq) {
            $charStream->shouldReceive('readBytes')
                       ->once()
                       ->andReturn([ord('a')]);
        }
        $charStream->shouldReceive('readBytes')
                    ->zeroOrMoreTimes()
                    ->andReturn(false);

        $encoder = new Swift_Mime_ContentEncoder_QpContentEncoder($charStream);
        $encoder->encodeByteStream($os, $is, 22);
        $this->assertEquals(
            str_repeat('a', 53)."=\r\n".str_repeat('a', 75)."=\r\n".str_repeat('a', 13),
            $collection->content
            );
    }

    public function testObserverInterfaceCanChangeCharset()
    {
        $stream = $this->createCharacterStream();
        $stream->shouldReceive('setCharacterSet')
               ->once()
               ->with('windows-1252');

        $encoder = new Swift_Mime_ContentEncoder_QpContentEncoder($stream);
        $encoder->charsetChanged('windows-1252');
    }

    public function testTextIsPreWrapped()
    {
        $encoder = $this->createEncoder();

        $input = str_repeat('a', 70)."\r\n".
                 str_repeat('a', 70)."\r\n".
                 str_repeat('a', 70);

        $os = new Swift_ByteStream_ArrayByteStream();
        $is = new Swift_ByteStream_ArrayByteStream();
        $is->write($input);

        $encoder->encodeByteStream($is, $os);

        $this->assertEquals(
            $input, $os->read(PHP_INT_MAX)
            );
    }

    private function createCharacterStream($stub = false)
    {
       