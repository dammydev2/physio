($this->once())
                ->method('createTextHeader')
                ->with('Subject', 'some text')
                ->will($this->returnValue($this->createHeader('Subject')));

        $set = $this->createSet($factory);
        $set->addTextHeader('Subject', 'some text');
        $this->assertTrue($set->has('Subject'));
    }

    public function testAddedParameterizedHeaderIsSeenByHas()
    {
        $factory = $this->createFactory();
        $factory->expects($this->once())
                ->method('createParameterizedHeader')
                ->with('Content-Type', 'text/plain', ['charset' => 'utf-8'])
                ->will($this->returnValue($this->createHeader('Content-Type')));

        $set = $this->createSet($factory);
        $set->addParameterizedHeader('Content-Type', 'text/plain',
            ['charset' => 'utf-8']
            );
        $this->assertTrue($set->has('Content-Type'));
    }

    public function testAddedIdHeaderIsSeenByHas()
    {
        $factory = $this->createFactory();
        $factory->expects($this->once())
                ->method('createIdHeader')
                ->with('Message-ID', 'some@id')
                ->will($this->returnValue($this->createHeader('Message-ID')));

        $set = $this->createSet($factory);
        $set->addIdHeader('Message-ID', 'some@id');
        $this->assertTrue($set->has('Message-ID'));
    }

    public function testAddedPathHeaderIsSeenByHas()
    {
        $factory = $this->createFactory();
        $factor