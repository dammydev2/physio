       $textChild = new MimeEntityFixture(Swift_Mime_SimpleMimeEntity::LEVEL_ALTERNATIVE,
            "Content-Type: text/plain\r\n".
            "\r\n".
            'TEXT PART',
            'text/plain'
            );
        $headers = $this->createHeaderSet([], false);
        $headers->shouldReceive('toString')
                ->zeroOrMoreTimes()
                ->andReturn("Content-Type: multipart/alternative; boundary=\"xxx\"\r\n");

        $entity = $this->createEntity($headers, $this->createEncoder(),
            $this->createCache()
            );
        $entity->setBoundary('xxx');
        $entity->setChildren([$htmlChild, $textChild]);

        $this->assertEquals(
            "Content-Type: multipart/alternative; boundary=\"xxx\"\r\n".
            "\r\n\r\n--xxx\r\n".
            "Content-Type: text/plain\r\n".
            "\r\n".
            'TEXT PART'.
            "\r\n\r\n--xxx\r\n".
            "Content-Type: text/html\r\n".
            "\r\n".
            'HTML PART'.
            "\r\n\r\n--xxx--\r\n",
            $entity->toString()
            );
    }

    public function testOrderingEqualContentTypesMaintainsOriginalOrdering()
    {
        $firstChild = new MimeEntityFixture(Swift_Mime_SimpleMimeEntity::LEVEL_ALTERNATIVE,
            "Content-Type: text/p