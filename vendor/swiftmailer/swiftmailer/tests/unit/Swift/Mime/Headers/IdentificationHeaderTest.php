e()
                ->andReturn(['foo@bar' => null]);
        $buf->shouldReceive('write')
            ->once()
            ->with("DATA\r\n")
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn('354 OK'."\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with("\r\n.\r\n")
            ->andReturn(2);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(2)
            ->andReturn('554 Error'."\r\n");

        $this->finishBuffer($buf);
        try {
            $smtp->start();
            $smtp->send($message);
            $this->fail('250 is the expected response after a DATA transmission (not observed)');
        } catch (Swift_TransportException $e) {
        }
    }

    public function testBccRecipientsAreRemovedFromHeaders()
    {
        /* -- RFC 2821, 7.2.

     Addresses that do not appear in the message headers may appear in the
     RCPT commands to an SMTP server for a number of reasons.  The two
     most common involve the use of a mailing address as a "list exploder"
     (a single address that resolves into multiple addresses) and the
     appearance of "blind copies".  Especially when more than one RCPT
     command is present, and in order to avoid defeating some of the
     purpose of these mechanisms, SMTP clients and servers SHOULD NOT copy
     the full set of RCPT command arguments into the headers, either as
     part of trace headers or as informational or private-extension
     headers.  Since this rule is often violated in practice, and cannot
     be enforced, sending SMTP systems that are aware of "bcc" use MAY
     find it helpful to send each blind copy as a separate message
     transaction containing only a single RCPT command.
     */

        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $message = $this->createMessage();
        $message->shouldReceive('getFrom')
                ->zeroOrMoreTimes()
                ->andReturn(['me@domain.com' => 'Me']);
        $message->shouldReceive('getTo')
                ->zeroOrMoreTimes()
                ->andReturn(['foo@bar' => null]);
        $message->shouldReceive('getBcc')
                ->zeroOrMoreTimes()
                ->andReturn([
                    'zip@button' => 'Zip Button',
                    'test@domain' => 'Test user',
                ]);
        $message->shouldReceive('setBcc')
                ->once()
                ->with([]);
        $message->shouldReceive('setBcc')
                ->zeroOrMoreTimes();

        $this->finishBuffer($buf);
        $smtp->start();
        $smtp->send($message);
    }

    public function testEachBccRecipientIsSentASeparateMessage()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $message = $this->createMessage();

        $message->shouldReceive('getFrom')
                ->zeroOrMoreTimes()
                ->andReturn(['me@domain.com' => 'Me']);
        $message->shouldReceive('getTo')
                ->zeroOrMoreTimes()
                ->andReturn(['foo@bar' => null]);
        $message->shouldReceive('getBcc')
                ->zeroOrMoreTimes()
                ->andReturn([
                    'zip@button' => 'Zip Button',
                    'test@domain' => 'Test user',
                ]);
        $message->shouldReceive('setBcc')
                ->atLeast()->once()
                ->with([]);
        $message->shouldReceive('setBcc')
                ->once()
                ->with(['zip@button' => 'Zip Button']);
        $message->shouldReceive('setBcc')
                ->once()
                ->with(['test@domain' => 'Test user']);
        $message->shouldReceive('setBcc')
                ->atLeast()->once()
                ->with([
                    'zip@button' => 'Zip Button',
                    'test@domain' => 'Test user',
                ]);

        $buf->shouldReceive('write')->once()->with("MAIL FROM:<me@domain.com>\r\n")->andReturn(1);
        $buf->shouldReceive('readLine')->once()->with(1)->andReturn("250 OK\r\n");
        $buf->shouldReceive('write')->once()->with("RCPT TO:<foo@bar>\r\n")->andReturn(2);
        $buf->shouldReceive('readLine')->once()->with(2)->andReturn("250 OK\r\n");
        $buf->shouldReceive('write')->once()->with("DATA\r\n")->andReturn(3);
        $buf->shouldReceive('readLine')->once()->with(3)->andReturn("354 OK\r\n");
        $buf->shouldReceive('write')->once()->with("\r\n.\r\n")->andReturn(4);
        $buf->shouldReceive('readLine')->once()->with(4)->andReturn("250 OK\r\n");

        $buf->shouldReceive('write')->once()->with("MAIL FROM:<me@domain.com>\r\n")->andReturn(5);
        $buf->shouldReceive('readLine')->once()->with(5)->andReturn("250 OK\r\n");
        $buf->shouldReceive('write')->once()->with("RCPT TO:<zip@button>\r\n")->andReturn(6);
        $buf->shouldReceive('readLine')->once()->with(6)->andReturn("250 OK\r\n");
        $buf->shouldReceive('write')->once()->with("DATA\r\n")->andReturn(7);
        $buf->shouldReceive('readLine')->once()->with(7)->andReturn("354 OK\r\n");
        $buf->shouldReceive('write')->once()->with("\r\n.\r\n")->andReturn(8);
        $buf->shouldReceive('readLine')->once()->with(8)->andReturn("250 OK\r\n");

        $buf->shouldReceive('write')->once()->with("MAIL FROM:<me@domain.com>\r\n")->andReturn(9);
        $buf->shouldReceive('readLine')->once()->with(9)->andReturn("250 OK\r\n");
        $buf->shouldReceive('write')->once()->with("RCPT TO:<test@domain>\r\n")->andReturn(10);
        $buf->shouldReceive('readLine')->once()->with(10)->andReturn("250 OK\r\n");
        $buf->shouldReceive('write')->once()->with("DATA\r\n")->andReturn(11);
        $buf->shouldReceive('readLine')->once()->with(11)->andReturn("354 OK\r\n");
        $buf->shouldReceive('write')->once()->with("\r\n.\r\n")->andRetu