 "Content-Type: text/plain\r\n".
            "\r\n".
            'foobar'
            );

        $attachment = $this->createChild(Swift_Mime_SimpleMimeEntity::LEVEL_MIXED,
            "Content-Type: application/octet-stream\r\n".
            "\r\n".
            'data'
            );

        $headers->shouldReceive('toString')
              ->zeroOrMoreTimes()
              ->andReturn("Content-Type: multipart/mixed; boundary=\"xxx\"\r\n");
        $headers->shouldReceive('newInstance')
              ->zeroOrMoreTimes()
              ->andReturn($newHeaders);
        $newHeaders->shouldReceive('toString')
              ->zeroOrMoreTimes()
              ->andReturn("Content-Type: multipart/alternative; boundary=\"yyy\"\r\n");

        $entity = $this->createEntity($headers, $this->createEncoder(),
            $this->createCache()
            );
        $entity->setBoundary('xxx');
        $entity->setChildren([$part, $attachment]);

        $this->assertRegExp(
            '~^'.
            "Content-Type: multipart/mixed; boundary=\"xxx\"\r\n".
            "\r\n\r\n--xxx\r\n".
            "Content-Type: multipart/alternative; boundary=\"yyy\"\r\n".
            "\r\n\r\n--(.*?)\r\n".
            "Content-Type: