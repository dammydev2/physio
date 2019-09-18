 3.6.5.
     */

        $subject = $this->createHeader('Subject', 'example subject');
        $message = $this->createMessage(
            $this->createHeaderSet(['Subject' => $subject]),
            $this->createEncoder(), $this->createCache()
            );
        $this->assertEquals('example subject', $message->getSubject());
    }

    public function testSubjectIsSetInHeader()
    {
        $subject = $this->createHeader('Subject', '', [], false);
        $subject->shouldReceive('setFieldBodyModel')
                ->once()
                ->with('foo');

        $message = $this->createMessage(
            $this->createHeaderSet(['Subject' => $subject]),
            $this->createEncoder(), $this->createCache()
            );
        $message->setSubject('foo');
    }

    public function testSubjectHeaderIsCreatedIfNotPresent()
    {
        $headers = $this->createHeaderSet([], false);
        $headers->shouldReceive('addTextHeader')
                ->once()
                ->with('Subject', 'example subject');
        $headers->shouldReceive('addTextHeader')
                ->zeroOrMoreTimes();

        $message = $this->createMessage($headers, $this->createEncoder(),
            $this->createCache()
            );
        $message->setSubject('example subject');
    }

    public function testReturnPathIsReturnedFromHeader()
    {
        /* -- RFC 2822, 3.6.7.
     */

        $path = $this->createHeader('Return-Path', 'bounces@domain');
        $message = $this->createMessage(
            $this->createHeaderSet(['Return-Path' => $path]),
            $this->createEncoder(), $this->createCache()
            );
        $this->assertEquals('bounces@domain', $message->getReturnPath());
    }

    public function testReturnPathIsSetInHeader()
    {
        $path = $this->createHeader('Return-Path', '', [], false);
        $path->shouldReceive('setFieldBodyModel')
             ->once()
             ->with('bounces@domain');

        $message = $this->createMessage(
            $this->createHeaderSet(['Return-Path' => $path]),
            $this->createEncoder(), $this->createCache()
            );
        $message->setReturnPath('bounces@domain');
    }

    public function testReturnPathHeaderIsAddedIfNoneSet()
    {
        $headers = $this->createHeaderSet([], false);
        $headers->shouldReceive('addPathHeader')
                ->once()
                ->with('Return-Path', 'bounces@domain');

        $message = $this->createMessage($headers, $this->createEncoder(),
            $this->createCache()
            );
        $message->setReturnPath('bounces@domain');
    }

    public function testSenderIsReturnedFromHeader()
    {
        /* -- RFC 2822, 3.6.2.
     */

        $sender = $this->createHeader('Sender', ['sender@domain' => 'Name']);
        $message = $this->createMessage(
            $this->createHeaderSet(['Sender' => $sender]),
            $this->createEncoder(), $this->createCache()
            );
        $this->assertEquals(['sender@domain' => 'Name'], $message->getSender());
    }

    public function testSenderIsSetInHeader()
    {
        $sender = $this->createHeader('Sender', ['sender@domain' => 'Name'],
            [], false
            );
        $sender->shouldReceive('setFieldBodyModel')
               ->once()
               ->with(['other@domain' => 'Other']);

        $message = $this->createMessage(
            $this->createHeaderSet(['Sender' => $sender]),
            $this->createEncoder(), $this->createCache()
            );
        $message->setSender(['other@domain' => 'Other']);
    }

    public function testSenderHeaderIsAddedIfNoneSet()
    {
        $headers = $this->createHeaderSet([], false);
        $headers->shouldReceive('addMailboxHeader')
                ->once()
                ->with('Sender', (array) 'sender@domain');
        $headers->shouldReceive('addMailboxHeader')
                ->zeroOrMoreTimes();

        $message = $this->createMessage($headers, $this->createEncoder(),
            $this->createCache()
            );
        $message->setSender('sender@domain');
    }

    public function testNameCanBeUsedInSenderHeader()
    {
        $headers = $this->createHeaderSet([], false);
        $headers->shouldReceive('addMailboxHeader')
                ->once()
                ->with('Sender', ['sender@domain' => 'Name']);
        $headers->shouldReceive('addMailboxHeader')
                ->zeroOrMoreTimes();

        $message = $this->createMessage($headers, $this->createEncoder(),
            $this->createCache()
            );
        $message->setSender('sender@domain', 'Name');
    }

    public function testFromIsReturnedFromHeader()
    {
        /* -- RFC 2822, 3.6.2.
     */

        $from = $this->createHeader('From', ['from@domain' => 'Name']);
        $message = $this->createMessage(
            $this->createHeaderSet(['From' => $from]),
            $this->createEncoder(), $this->createCache()
            );
        $this->assertEquals(['from@domain' => 'Name'], $message->getFrom());
    }

    public function testFromIsSetInHeader()
    {
        $from = $this->createHeader('From', ['from@domain' => 'Name'],
            [], false
            );
        $from->shouldReceive('setFieldBodyModel')
             ->once()
             ->with(['other@domain' => 'Other']);

        $message = $this->createMessage(
            $this->createHeaderSet(['From' => $from]),
            $this->createEncoder(), $this->createCache()
            );
        $message->setFrom(['other@domain' => 'Other']);
    }

    public function testFromIsAddedToHeadersDuringAddFrom()
    {
        $from = $this->createHeader('From', ['from@domain' => 'Name'],
            [], false
            );
        $from->shouldReceive('setFieldBodyModel')
  