ficationHeader('Message-ID', 'some@id');
        $headers = new Headers();
        $headers->addIdHeader('Message-ID', 'some@id');
        $this->assertEquals($header->toString(), $headers->get('Message-ID')->toString());
    }

    public function testGetReturnsNullIfHeaderNotSet()
    {
        $headers = new Headers();
        $this->assertNull($headers->get('Message-ID'));
    }

    public function testGetAllReturnsAllHeadersMatchingName()
    {
        $header0 = new UnstructuredHeader('X-Test', 'some@id');
        $header1 = new UnstructuredHeader('X-Test', 'other@id');
        $header2 = new UnstructuredHeader('X-Test', 'more@id');
        $headers = new Headers();
        $headers->addTextHeader('X-Test', 'some@id');
        $headers->addTextHeader('X-Test', 'other@id');
        $headers->addTextHeader('X-Test', 'more@id');
        $this->assertEquals([$header0, $header1, $header2], iterator_to_array($headers->getAll('X-Test')));
    }

    public function testGetAllReturnsAllHeadersIfNoArguments()
    {
        $header0 = new IdentificationHeader('Message-ID', 'some@id');
        $header1 = new UnstructuredHeader('Subject', 'thing');
        $header2 = new MailboxListHeader('To', [new Address('person@example.org')]);
        $headers = new Headers();
        $headers->addIdHeader('Message-ID', 'some@id');
        $headers->addTextHeader('Subject', 'thing');
        $headers->addMailboxListHeader('To', [new Address('person@example.org')]);
        $this->assertEquals(['message-id' => $header0, 'subject' => $header1, 'to' => $h