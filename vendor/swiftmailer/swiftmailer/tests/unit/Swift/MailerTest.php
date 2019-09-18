is->returnValue($header));

        $set = $this->createSet($factory);
        $set->addIdHeader('Message-ID', 'some@id');
        $this->assertTrue($set->has('message-id'));
    }

    public function testGetIsNotCaseSensitive()
    {
        $header = $this->createHeader('Message-ID');
        $factory = $this->createFactory();
        $factory->expects($this->at(0))
                ->method('createIdHeader')
                ->with('Message-ID', 'some@id')
                ->will($this->returnValue($header));

        $set = $this->createSet($factory);
        $set->addIdHeader('Message-ID', 'some@id');
        $this->assertSame($header, $set->get('message-id'));
    }

    public function testGetAllIsNotCaseSensitive()
    {
        $header = $this->createHeader('Message-ID');
        $factory = $this->createFactory();
        $factory->expects($this->at(0))
                ->method('createIdHeader')
                ->with('Message-ID', 'some@id')
                ->will($this->returnValue($header));

        $set = $this->createSet($factory);
        $set->addIdHeader('Message-ID', 'some@id');
        $this->assertEquals([$header], $set->getAll('message-id'));
    }

    public function testRemoveIsNotCaseSensitive()
    {
        $header = $this->createHeader('Message-ID');
        $factory = $this->createFactory();
        $factory->expects($this->at(0))
                ->method('createIdHeader')
                ->with('Message-ID', 'some@id')
                ->will($this->returnValue($header));

        $set = $this->createSet($factory);
        $set->addIdHeader('Message-ID', 'some@id');
        $set->remove('message-id');
        $this->assertFalse($set->has('Message-ID'));
    }

    public function testRemoveAllIsNotCaseSensitive()
    {
        $header = $this->createHeader('Message-ID');
        $factory = $this->createFactory();
        $factory->expects($this->at(0))
                ->method('createIdHeader')
                ->with('Message-ID', 'some@id')
                ->will($this->returnValue($header));

        $set = $this->createSet($factory);
        $set->addIdHeader('Message-ID', 'some@id');
        $set->removeAll('message-id');
        $this->assertFalse($set->has('Message-ID'));
    }

    public function testToStringJoinsHeadersTogether()
    {
        $factory = $this->createFactory();
        $factory->expects($this->at(0))
                ->method('createTextHeader')
                ->with('Foo', 'bar')
                ->will($this->returnValue($this->createHeader('Foo', 'bar')));
        $factory->expects($this->at(1))
                ->method('createTextHeader')
                ->with('Zip', 'buttons')
                ->will($this->returnValue($this->createHeader('Zip', 'buttons')));

        $set = $this->createSet($factory);
        $set->addTextHeader('Foo', 'bar');
        $set->addTextHeader('Zip', 'buttons');
        $this->assertEquals(
            "Foo: bar\r\n".
            "Zip: buttons\r\n",
            $set->toString()
            );
    }

    public function testHeadersWithoutBodiesAreNotDisplayed()
    {
        $factory = $this->createFactory();
        $factory->expects($this->at(0))
                ->method('createTextHeader')
                ->with('Foo', 'bar')
                ->will($this->returnValue($this->createHeader('Foo', 'bar')));
        $factory->expects($this->at(1))
                ->method('createTextHeader')
                ->with('Zip', '')
                ->will($this->returnValue($this->createHeader('Zip', '')));

        $set = $this->createSet($factory);
        $set->addTextHeader('Foo', 'bar');
        $set->addTextHeader('Zip', '');
        $this->assertEquals(
            "Foo: bar\r\n",
            $set->toString()
            );
    }

    public function testHeadersWithoutBodiesCanBeForcedToDisplay()
    {
        $factory = $this->createFactory();
        $factory->expects($this->at(0))
                ->method('createTextHeader')
                ->with('Foo', '')
                ->will($this->returnValue($this->createHeader('Foo', '')));
        $factory->expects($this->at(1))
                ->method('createTextHeader')
                ->with('Zip', '')
                ->will($this->returnValue($this->createHeader('Zip', '')));

        $set = $this->createSet($factory);
        $set->addTextHeader('Foo', '');
        $set->addTextHeader('Zip', '');
        $set->setAlwaysDisplayed(['Foo', 'Zip']);
        $this->assertEquals(
            "Foo: \r\n".
            "Zip: \r\n",