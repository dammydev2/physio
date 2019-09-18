)
            ->once()
            ->with(2)
            ->andReturn("500 Not now\r\n");
        $dispatcher->shouldReceive('createSendEvent')
                   ->zeroOrMoreTimes()
                   ->with($smtp, \Mockery::any())
                   ->andReturn($evt);
        $dispatcher->shouldReceive('dispatchEvent')
                   ->once()
                   ->with($evt, 'sendPerformed');
        $dispatcher->shouldReceive('dispatchEvent')
                   ->zeroOrMoreTimes();
        $evt->shouldReceive('bubbleCancelled')
            ->zeroOrMoreTimes()
            ->andReturn(false);
        $evt->shouldReceive('setFailedRecipients')
            ->once()
            ->with(['mark@swiftmailer.org']);

        $this->finishBuffer($buf);
        $smtp->start();
        $this->assertEquals(0, $smtp->send($message));
    }

    public function testSendEventHasResultFailedIfAllFailures()
    {
        $buf = $this->getBuffer();
        $dispatcher = $this->createEventDispatcher(false);
        $evt = $this->getMockery('Swift_Events_SendEvent')->shouldIgnoreMissing();
        $smtp = $this->getTransport($buf, $dispatcher);
        $message = $this->createMessage();

        $message->shouldReceive('getFrom')
                ->zeroOrMoreTimes()
                ->andReturn(['chris@swiftmailer.org' => null]);
        $message->shouldReceive('getTo')
                ->zeroOrMoreTimes()
                ->andReturn(['mark@swiftmailer.org' => 'Mark']);
        $buf->shouldReceive('write')
            ->once()
            ->with("MAIL FROM:<chris@swiftmailer.org>\r\n")
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn("250 OK\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with("RCPT TO:<mark@swiftmailer.org>\r\n")
            ->andReturn(2);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(2)
            ->andReturn("500 Not now\r\n");
        $dispatcher->shouldReceive('createSendEvent')
                   ->zeroOrMoreTimes()
                   ->with($smtp, \Mockery::any())
                   ->andReturn($evt);
        $dispatcher->shouldReceive('dispatchEvent')
                   ->once()
                   ->with($evt, 'sendPerformed');
        $dispatcher->shouldReceive('dispatchEvent')
                   ->zeroOrMoreTimes();
        $evt->shouldReceive('bubbleCancelled')
            ->zeroOrMoreTimes()
            ->andReturn(false);
        $evt->shouldReceive('setResult')
            ->once()
            ->with(Swift_Events_SendEvent::RESULT_FAILED);

        $this->finishBuffer($buf);
        $smtp->start();
        $this->assertEquals(0, $smtp->send($message));
    }

    public function testSendEventHasResultTentativeIfSomeFailures()
    {
        $buf = $this->getBuffer();
        $dispatcher = $this->createEventDispatcher(false);
        $evt = $this->getMockery('Swift_Events_SendEvent')->shouldIgnoreMissing();
        $smtp = $this->getTransport($buf, $dispatcher);
        $message = $this->createMessage();

        $message->shouldReceive('getFrom')
                ->zeroOrMoreTimes()
                ->andReturn(['chris@swiftmailer.org' => null]);
        $message->shouldReceive('getTo')
                ->zeroOrMoreTimes()
                ->andReturn([
                    'mark@swiftmailer.org' => 'Mark',
                    'chris@site.tld' => 'Chris',
                ]);
        $buf->shouldReceive('write')
            ->once()
            ->with("MAIL FROM:<chris@swiftmailer.org>\r\n")
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn("250 OK\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with("RCPT TO:<mark@swiftmailer.org>\r\n")
            ->andReturn(2);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(2)
            ->andReturn("500 Not now\r\n");
        $dispatcher->shouldReceive('createSendEvent')
                   ->zeroOrMoreTimes()
                   ->with($smtp, \Mockery::any())
                   ->andReturn($evt);
        $dispatcher->shouldReceive('dispatchEvent')
                   ->once()
                   ->with($evt, 'sendPerformed');
        $dispatcher->shouldReceive('dispatchEvent')
                   ->zeroOrMoreTimes();
        $evt->shouldReceive('bubbleCancelled')
            ->zeroOrMoreTimes()
            ->andReturn(false);
        $evt->shouldReceive('setResult')
            ->once()
            ->with(Swift_Events_SendEvent::RESULT_TENTATIVE);

        $this->finishBuffer($buf);
        $smtp->start();
        $this->assertEquals(1, $smtp->send($message));
    }

    public function testSendEventHasResultSuccessIfNoFailures()
    {
        $buf = $this->getBuffer();
        $dispatcher = $this->createEventDispatcher(false);
        $evt = $this->getMockery('Swift_Events_SendEvent')->shouldIgnoreMissing();
        $smtp = $this->getTransport($buf, $dispatcher);
        $message = $this->createMessage();

        $message->shouldReceive('getFrom')
                ->zeroOrMoreTimes()
                ->andReturn(['chris@swiftmailer.org' => null]);
        $message->shouldReceive('getTo')
             