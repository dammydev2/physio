->setCharset('iso-8859-1');
        $this->assertEquals('X-Test: =?'.$header->getCharset().'?Q?=8F?=', $header->toString());
    }

    public function testEncodedWordsAreUsedToEncodedNonPrintableAscii()
    {
        // SPACE and TAB permitted
        $nonPrintableBytes = array_merge(range(0x00, 0x08), range(0x10, 0x19), [0x7F]);
        foreach ($nonPrintableBytes as $byte) {
            $char = pack('C', $byte);
            $encodedChar = sprintf('=%02X', $byte);
            $header = new UnstructuredHeader('X-A', $char);
            $header->setCharset('iso-8859-1');
            $this->assertEquals('X-A: =?'.$header->getCharset().'?Q?'.$encodedChar.'?=', $header->toString(), 'Non-printable ascii should be encoded');
        }
    }

    public function testEncodedWordsAreUsedToEncode8BitOctets()
    {
        foreach (range(0x80, 0xFF) as $byte) {
            $char = pack('C', $byte);
            $encodedChar = sprintf('=%02X', $byte);
            $header = new UnstructuredHeader('X-A', $char);
            $header->setCharset('iso-8859-1');
            $this->assertEquals('X-A: =?'.$header->getCharset().'?Q?'.$encodedChar.'?=', $header->toString(), '8-bit octets should be encoded');
        }
    }

    public function testEncodedWordsAreNoMoreThan75CharsPerLine()
    {
        /* -- RFC 2047, 2.
        An 'encoded-word' may not be more than 75 characters long, including
        'charset', 