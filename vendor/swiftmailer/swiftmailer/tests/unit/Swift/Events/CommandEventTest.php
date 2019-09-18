d('(')]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([ord('"')]);
        $charStream->shouldReceive('readBytes')
                   ->once()
                   ->andReturn([ord(')')]);
        $charStream->shouldReceive('readBytes')
                   ->zeroOrMoreTimes()
                   ->andReturn(false);

        $encoder = $this->createEncoder($charStream);
        $this->assertEquals('=28=22=29', $encoder->encodeString('(")'),
            '%s: Chars (, " (DQUOTE) and ) may not appear as per RFC 2047.'
            );
    }

    public function testOnlyCharactersAllowedInPhrasesAreUsed()
    {
        /* -- RFC 2047, 5.
        (3) As a replacement for a 'word' entity within a 'phrase', for example,
        one that precedes an address in a From, To, or Cc header.  The ABNF
        definition for 'phrase' from RFC 822 thus becomes:

        phrase = 1*( encoded-word / word )

        In this case the set of characters that may be used in a "Q"-encoded