mes();

        $message = $this->createMessage($headers, $this->createEncoder(),
            $this->createCache()
            );
        $message->setBcc('bcc@domain');
    }

    public function testNameCanBeUsedInBcc()
    {
        $headers = $this->createHeaderSet([], false);
        $headers->shouldReceive('addMailboxHeader')
                ->once()
                ->with('Bcc', ['bcc@domain' => 'Name']);
        $headers->shouldReceive('addMailboxHeader')
                ->zeroOrMoreTimes();

        $message = $this->createMessage($headers, $this->createEncoder(),
            $this->createCache()
            );
        $message->setBcc('bcc@domain', 'Name');
    }

    public function testPriorityIsReadFromHeader()
    {
        $prio = $this->createHeader('X-Priority', '2 (High)');
        $message = $this->createMessage(
            $this->createHeaderSet(['X-Priority' => $prio]),
            $this->createEncoder(), $this->createCache()
            );
        $this->assertEquals(2, $message->getPriority());
    }

    public function testPriorityIsSetInHeader()
    {
        $prio = $this->createHeader('X-Priority', '2 (High)', [], false);
        $prio->shouldReceive('setFieldBodyModel')
             ->once()
             ->with('5 (Lowest)');

        $message = $this->createMessage(
            $this->createHeaderSet(['X-Priority' => $prio]),
            $this->createEncoder(), $this->createCache()
            );
        $message->setPriority($message::PRIORITY_LOWEST);
    }

    public function testPriorityHeaderIsAddedIfNoneSet()
    {
        $headers = $this->createHeaderSet([], false);
        $headers->shouldReceive('addTextHeader')
                ->once()
                ->with('X-Priority', '4 (Low)');
        $headers->shouldRec