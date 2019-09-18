s->shouldReceive('addTextHeader')
                ->once()
                ->with('Content-Transfer-Encoding', 'something');
        $headers->shouldReceive('addTextHeader')
                ->zeroOrMoreTimes();

        $entity = $this->createEntity($headers, $this->createEncoder(),
            $this->createCache()
            );
        $entity->setEncoder($this->createEncoder('something'));
    }

    public function testIdIsReturnedFromHeader()
    {
        /* -- RFC 2045, 7.
        In constructing a high-level user agent, it may be desirable to allow
        one body to make reference to another.  Accordingly, bodies may be
        labelled using the "Content-ID" header field, which is syntactically
        identical to the "Message-ID" header field
   