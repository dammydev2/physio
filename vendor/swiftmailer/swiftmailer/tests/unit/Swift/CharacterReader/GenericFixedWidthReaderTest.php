    {
        $replyTo = $this->createHeader('Reply-To', ['from@domain' => 'Name'],
            [], false
            );
        $replyTo->shouldReceive('setFieldBodyModel')
                ->once()
                ->with(['from@domain' => 'Name', 'other@domain' => 'Other']);

        $message = $this->createMessage(
            $this->createHeaderSet(['Reply-To' => $replyTo]),
            $this->createEncoder(), $this->createCache()
            );
        $message->addReplyTo('other@domain', 'Other');
    }

    public function testReplyToHeaderIsAddedIfNoneSet()
    {
        $headers = $this->createHeaderSet([], false);
        $headers->shouldReceive('addMailboxHeader')
                ->once()
                ->with('Reply-To', (array) 'reply@domain');
        $headers->shouldReceive('addMailboxHeader')
                ->zeroOrMoreTimes();

        $message = $this->createMessage($headers, $this->createEncoder(),
            $this->createCache()
            );
        $message->setReplyTo('reply@domain');
    }

    public function testNameCanBeUsedInReplyTo()
    {
        $headers = $this->createHeaderSet([], false);
        $headers->shouldReceive('addMailboxHeader')
                ->once()
                ->with('Reply-To', ['reply@domain' => 'Name']);
        $headers->shouldReceive('addMailboxHeader')
               