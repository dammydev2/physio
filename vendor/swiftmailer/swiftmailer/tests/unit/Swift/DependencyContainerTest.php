age-ID', 'some@id')
                ->will($this->returnValue($header));

        $set = $this->createSet($factory);
        $set->addIdHeader('Message-ID', 'some@id');
        $this->assertSame($header, $set->get('Message-ID'));
    }

    public function testGetWithSpeiciedOffset()
    {
        $header0 = $this->createHeader('Message-ID');
        $header1 = $this->createHeader('Message-ID');
        $header2 = $this->createHeader('Message-ID');
        $factory = $this->createFactory();
        $factory->expects($this->at(0))
                ->method('createIdHeader')
                ->with('Message-ID', 'some@id')
                ->will($this->returnValue($header0));
        $factory->expects($this->at(1))
                ->method('createIdHeader')
                ->with('Message-ID', 'other@id')
                ->will($this->returnValue($header1));
        $factory->expects($this->at(2))
                ->method('createIdHeader')
                ->with('Message-ID', 'more@id')
                ->will($this->returnValue($header2));

        $set = $this->createSet($factory);
        $set->addIdHeader('Message-ID', 'some@id');
        $set->addIdHeader('Message-ID', 'other@id');
        $set->addIdHeader('Message-ID', 'more@id');
        $this->assertSame($header1, $set->get('Message-ID', 1));
    }

    public function testGetReturnsNullIfHeaderNotSet()
    {
        $set = $this->createSet($this->createFactory());
        $this->assertNull($set->get('Message-ID', 99));
    }

    public function testGetAllReturnsAllHeadersMatchingName()
    {
        $header0 = $this->createHeader('Message-ID');
        $header1 = $this->createHeader('Message-ID');
        $header2 = $this->createHeader('Message-ID');
        $factory = $this->createFactory();
        $factory->expects($this->at(0))
                ->method('createIdHeader')
                ->with('Message-ID', 'some@id')
                ->will($this->returnValue($header0));
        $factory->expects($this->at(1))
                ->method('createIdHeader')
                ->with('Message-ID', 'other@id')
                ->will($this->returnValue($header1));
        $factory->expects($this->at(2))
                ->method('createIdHeader')
                ->with('Message-ID', 'more@id')
                ->will($this->returnValue($header2));

        $set = $this->createSet($factory);
        $set->addIdHeader('Message-ID', 'some@id');
        $set->addIdHeader('Message-ID', 'other@id');
        $set->addIdHeader('Message-ID', 'more@id');

        $this->assertEquals([$header0, $header1, $header2],
            $set->getAll('Message-ID')
            );
    }

    public function testGetAllReturnsAllHeadersIfNoArguments()
    {
        $header0 = $this->createHeader('Message-ID');
        $header1 = $this->createHeader('Subject');
        $header2 = $this->createHeader('To');
        $factory = $this->createFactory();
        $factory->expects($this->at(0))
                ->method('createIdHeader')
                ->with('Message-ID', 'some@id')
                ->will($this->returnValue($header0));
        $factory->expects($this->at(1))
                ->method('createIdHeader')
                ->with('Subject', 'thing')
                ->will($this->returnValue($header1));
        $factory->expects($this->at(2))
                ->method('createIdHeader')
                ->with('To', 'person@example.org')
                ->will($this->returnValue($header2));

        $set = $this->createSet($factory);
        $set->addIdHeader('Message-ID', 'some@id');
        $set->addIdHeader('Subject', 'thing');
        $set->addIdHeader('To', 'person@example.org');

        $this->assertEquals([$header0, $header1, $header2],
            $set->getAll()
            );
    }

    public function testGetAllReturnsEmptyArrayIfNoneSet()
    {
        $set = $this->createSet($this->createFactory());
        $this->assertEquals([], $set->getAll('Received'));
    }

    public function testRemoveWithUnspecifiedOffset()
    {
        $header = $this->createHeader('Message-ID');
        $factory = $this->createFactory();
        $factory->expects($this->at(0))
                ->method('createIdHeader')
                ->with('Message-ID', 'some@id')
                ->will($this->returnValue($header));

        $set = $this->createSet($factory);
        $set->addIdHeader('Message-ID', 'some@id');
        $set->remove('Message-ID');
        $this->assertFalse($set->has('Message-ID'));
    }

    public function testRemoveWithSpecifiedIndexRemovesHeader()
    {
        $header0 = $this->createHeader('Message-ID');
        $header1 = $this->createHeader('Message-ID');
        $factory = $this->createFactory();
        $factory->expects($this->at(0))
                ->method('createIdHeader')
                ->with('Message-ID', 'some@id')
                ->will($this->returnValue($header0));
        $factory->expects($this->at(1))
                ->method('createIdHeader')
                ->with('Message-ID', 'other@id')
                ->will($this->returnValue($header1));

        $set = $this->createSet($factory);
        $set->addIdHeader('Message-ID', 'some@id');
        $set->addIdHeader('Message-ID', 'other@id');
        $set->remove('Message-ID', 0);
        $this->assertFalse($set->has('Message-ID', 0));
        $this->assertTrue($set->has('Message-ID', 1));
        $this->assertTrue($set->has('Message-ID'));
        $set->remove('Message-ID', 1);
        $this->assertFalse($set->has('Message-ID', 1));
        $this->assertFalse($set->has('Message-ID'));
    }

    public function testRemoveWithSpecifiedIndexLeavesOtherHeaders()
    {
        $header0 = $this->createHeader('Message-ID');
        $header1 = $this->createHeader('Message-ID');
        $factory = $this->createFactory();
        $factory->expects($this->at(0))
                ->method('createIdHeader')
                ->with('Message-ID', 'some@id')
                ->will($this->returnValue($header0));
        $factory->expects($this->at(1))
                ->method('createIdHeader')
                ->with('Message-ID', 'other@id')
                ->will($this->returnValue($header1));

        $set = $this->createSet($factory);
        $set->addIdHeader('Message-ID', 'some@id');
        $set->addIdHeader('Message-ID', 'other@id');
        $set->remove('Message-ID', 1);
        $this->assertTrue($set->has('Message-ID', 0));
    }

    public function te