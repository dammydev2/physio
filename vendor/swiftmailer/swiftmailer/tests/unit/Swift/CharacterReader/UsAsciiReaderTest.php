his->createHeader('Cc', ['cc@domain' => 'Name']);
        $message = $this->createMessage(
            $this->createHeaderSet(['Cc' => $cc]),
            $this->createEncoder(), $this->createCache()
            );
        $this->assertEquals(['cc@domain' => 'Name'], $message->getCc());
    }

    public function testCcIsSetInHeader()
    {
        $cc = $this->createHeader('Cc', ['cc@domain' => 'Name'],
            [], false
            );
        $cc->shouldReceive('setFieldBodyModel')
           ->once()
           ->with(['other@domain' => 'Other']);

        $message = $this->createMessage(
            $this->createHeaderSet(['Cc' => $cc]),
            $this->createEncoder(), $this->createCache()
            );
        $message->setCc(['other@domain' => 'Other']);
    }

    public function testCcIsAddedToHeadersDuringAddCc()
    {
        $cc = $this->createHeader('Cc', ['from@domain' => 'Name'],
            [], false
            );
        $cc->shouldReceive('setFieldBodyModel')
           ->once()
           ->with(['from@domain' => 'Name', 'other@domain' => 'Other']);

        $message = $this->createMessage(
            $this->createHeaderSet(['Cc' => $cc]),
            $this->createEncoder(), $this->createCache()
            );
        $message->addCc('other@domain', 'Other');
    }

    public function testCcHeaderIsAddedIf