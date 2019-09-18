LineLength(78);
        $this->assertEquals(
            'attachment; '.
            'filename*0*=utf-8\'\''.str_repeat('a', 63).";\r\n ".
            'filename*1*='.str_repeat('a', 63).";\r\n ".
            'filename*2*='.str_repeat('a', 54),
            $header->getFieldBody()
            );
    }

    public function testEncodedParamDataIncludesCharsetAndLanguage()
    {
        /* -- RFC 2231, 4.
        Asterisks ("*") are reused to provide the indicator that language and
        character set information is present and encoding is being used. A
        single quote ("'") is used to delimit the character set and language
        information at the beginning of the parameter value. Percent signs
        ("%") are used as the encoding flag, which agrees with RFC 2047.

        Specifically, an asterisk at the end of a parameter name acts as an
        indicator that character set and language information may appear at
        the beginning of the parameter value. A single quote is used to
        separate the character set, language, and actual value information in
        the parameter value string, and an percent sign is used to flag
        octets encoded in hexadecimal.  For example:

                Content-Type: application/x-stuff;
         title*=us-ascii'en-us'This%20is%20%2A%2A%2Afun%2A%2A%2A

        Note that it is perfectly permissible to leave either the character
        set or language field blank.  Note also that the single quote
        delimiters MUST be present even when one of the field values is
        omitted.
        */

        $value = str_repeat('a', 20).pack('C', 0x8F).str_repeat('a', 10);

        $encoder = $this->getParameterEncoder();
        $encoder->shouldReceive('encodeString')
                ->once()
                ->with($value, 12, 62, \Mockery::any())
                ->andReturn(str_repeat('a', 20).'%8F'.str_repeat('a', 10));

        $header = $this->getHeader('Content-Disposition',
            $this->getHeaderEncoder('Q', true), $encoder
            );
        $header->setValue('attachment');
        $header->setParameters(['filename' => $value]);
        $header->setMaxLineLength(78);
        $header->setLanguage($this->lang);
        $this->assertEquals(
            'attachment; filename*='.$this->charset."'".$this->lang."'".
            str_repeat('a', 20).'%8F'.str_repeat('a', 10),
            $header->getFieldBody()
            );
    }

    public function testMultipleEncodedParamLinesAreFormattedCorrectly()
    {
        /* -- RFC 2231, 4.1.
        Character set and language information may be combined with the
        parameter continuation mechanism. For example:

        Content-Type: application/x-stuff
     title*0*=us-ascii'en'This%20is%20even%20more%20
     title*1*=%2A%2A%2Afun%2A%2A%2A%20
     title*2="isn't it!"

        Note that:

     (1)   Language and character set information only appear at
           the beginning of a given parameter value.

     (2)   Continuations do not provide a facility for using more
           than one character set or language in the same
           parameter value.

     (3)   A value presented using multiple continuations may
           contain a mixture of encoded and unencoded segments.

     (4)   The first segment of a continuation MUST be encoded if
           language and character set information are given.

     (5)   If the first segment of a continued parameter value is
           encoded the language and character set field delimiters
           MUST be present even when the fields are left blank.
        */

        $value = str_repeat('a', 20).pack('C', 0x8F).str_repeat('a', 60);

        $encoder = $this->getParameterEncoder();
        $encoder->shouldReceive('encodeString')
                ->once()
                ->with($value, 12, 62, \Mockery::any())
                ->andReturn(str_repeat('a', 20).'%8F'.str_repeat('a', 28)."\r\n".
                    str_repeat('a', 32));

        $header = $this->getHeader('Content-Disposition',
            $this->getHeaderEncoder('Q', true), $encoder
            );
        $header->setValue('attachment');
        $header->setParameters(['filename' => $value]);
        $header->setMaxLineLength(78);
        $header->setLanguage($this->lang);
        $this->assertEquals(
            'attachment; filename*0*='.$this->charset."'".$this->lang."'".
            str_repeat('a', 20).'%8F'.str_repeat('a', 28).";\r\n ".
            'filename*1*='.str_repeat('a', 32),
            $header->getFieldBody()
            );
    }

    public function testToString()
    {
        $header = $this->getHeader('Content-Type',
            $this->getHeaderEncoder('Q', true), $this->getParameterEncoder(true)
            );
        $header->setValue('text/html');
        $header->setParameters(['charset' => 'utf-8']);
        $this->assertEquals('Content-Type: text/html; charset=utf-8'."\r\n",
            $header->toString()
            );
    }

    public function testValueCanBeEncodedIfNonAscii()
    {
        $value = 'fo'.pack('C', 0x8F).'bar';

        $encoder = $this->getHeaderEncoder('Q');
        $encoder->shouldReceive('encodeString')
                ->once()
                ->with($value, \Mockery::any(), \Mockery::any(), \Mockery::any())
                ->andReturn('fo=8Fbar');

        $header = $this->getHeader('X-Foo', $encoder, $this->getParameterEncoder(true));
        $header->setValue($value);
        $header->setParameters(['lookslike' => 'foobar']);
        $this->assertEquals('X-Foo: =?utf-8?Q?fo=8Fbar?=; lookslike=foobar'."\r\n",
            $header->toString()
            );
    }

    public function testValueAndParamCanBeEncodedIfNonAscii()
    {
        $value = 'fo'.pack('C', 0x8F).'bar';

        $encoder = $this->getHeaderEncoder('Q');
        $encoder->shouldReceive('encodeString')
                ->once()
                ->with($value, \Mockery::any(), \Mockery::any(), \Mockery::any())
                ->andReturn('fo=8Fbar');

        $paramEncoder = $this->getParameterEncoder();
        $paramEncoder->shouldReceive('encodeString')
                     ->once()
                     ->with($value, \Mockery::any(), \Mockery::any(), \Mockery::any())
                     ->andReturn('fo%8Fbar');

        $header = $this->getHeader('X-Foo', $encoder, $paramEncoder);
        $header->setValue($value);
        $header->setParameters(['says' => $value]);
        $this->assertEquals("X-Foo: =?utf-8?Q?fo=8Fbar?=; says*=utf-8''fo%8Fbar\r\n",
            $header->toString()
            );
    }

    public function testParamsAreEncodedWithEncodedWordsIfNoParamEncoderSet()
    {
        $value = 'fo'.pack('C', 0x8F).'bar';

        $encoder = $this->getHeaderEncoder('Q');
        $encoder->shouldReceive('encodeString')
                ->once()
                ->with($value, \Mockery::any(), \Mockery::any(), \Mockery::any())
                ->andReturn('fo=8Fbar');

        $header = $this->getHeader('X-Foo', $encoder, null);
        $header->setValue('bar');
        $header->setParameters(['says' => $value]);
        $this->assertEquals("X-Foo: bar; says=\"=?utf-8?Q?fo=8Fbar?=\"\r\n",
            $header->toString()
            );
    }

    public function testLanguageInformationAppearsInEncodedWords()
    {
        /* -- RFC 2231, 5.
        5.  Language specification in Encoded Words

        RFC 2047 provides support for non-US-ASCII character sets in RFC 822
        message header comments, phrases, and any unstructured text field.
        This is done by defining an encoded word construct which can appear
        in any of these places.  Given that these are fields intended for
        display, it 