rns a 354 Intermediate reply and
        considers all succeeding lines up to but not including the end of
        mail data indicator to be the message text.

        -- RFC 2821, 4.1.1.4.

        The receiver normally sends a 354 response to DATA, and then treats
        the lines (strings ending in <CRLF> sequences, as described in
        section 2.3.7) following the command as mail data from the sender.
        This command causes the mail data to be appended to the mail data
        buffer.  The mail data may contain any of the 128 ASCII character
        codes, although experience has indicated that use of control
        characters other than SP, HT, CR, and LF may cause problems and
        SHOULD be avoided when possible.

        -- RFC 2821, 4.3.2.

        DATA
            I: 354 -> data -> S: 250
                                                E: 552, 554, 451, 452
            E: 451, 554, 503
        */

        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $message = $this->createMessage();

        $message->shouldReceive('getFrom')
                ->once()
                ->andReturn(['me@domain.com' => 'Me']);
        $message->shouldReceive('getTo')
                ->once()
                ->andReturn(['foo@bar' => null]);
        $buf->shouldReceive('write')
            ->once()
            ->with("DATA\r\n")
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn('354 Go ahead'."\r\n");

        $this->finishBuffer($buf);
        try {
            $smtp->start();
            $smtp->send($message);
        } catch (Exception $e) {
            $this->fail('354 is the expected response to DATA');
        }
    }

    public function testBadDataResponseCausesException()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $message = $this->createMessage();

        $message->shouldReceive('getFrom')
                ->once()
                ->andReturn(['me@domain.com' => 'Me']);
        $message->shouldReceive('getTo')
                ->once()
                ->andReturn(['foo@bar' => null]);
        $buf->shouldReceive('write')
            ->once()
            ->with("DATA\r\n")
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn('451 Bad'."\r\n");

        $this->finishBuffer($buf);
        try {
            $smtp->start();
            $smtp->send($message);
            $this->fail('354 is the expected response to DATA (not observed)');
        } catch (Swift_TransportException $e) {
        }
    }

    public function testMessageIsStreamedToBufferForData()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $message = $this->createMessage();

        $message->shouldReceive('getFrom')
                ->once()
                ->andReturn(['me@domain.com' => 'Me']);
        $message->shouldReceive('g