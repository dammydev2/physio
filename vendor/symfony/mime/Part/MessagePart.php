= file_get_contents($file);
        $this->assertEquals($content, $p->getBody());
        $this->assertEquals(base64_encode($content), $p->bodyToString());
        $this->assertEquals(base64_encode($content), implode('', iterator_to_array($p->bodyToIterable())));
        $this->assertEquals('image', $p->getMediaType());
        $this->assertEquals('gif', $p->getMediaSubType());
        $this->assertEquals(new Headers(
            new ParameterizedHeader('Content-Type', 'image/gif', ['name' => 'test.gif']),
            new UnstructuredHeader('Content-Transfer-Encoding', 'base64'),
            new ParameterizedHeader('Content-Disposition', 'attachment', ['name' => 'test.gif', 'filename' => 'test.gif'])
        ), $p->getPreparedHeaders());
    }

    public function testFromPathWithMeta()
    {
        $p = DataPart::fromPath($file = __DIR__.'/../Fixtures/mimetypes/test.gif', 'photo.gif', 'image/jpeg');
        $content = file_get_contents($file);
        $this->assertEquals($content, $p->getBody());
        $this->assertEquals(base64_encode($content), $p->bodyToString());
        $this->assertEquals(base64_encode($content), implode('', iterator_to_array($p->bodyToIterable())));
        $this->assertEquals('image', $p->getMediaType());
        $this->assertEquals('jpeg'