consists of the required destination
        mailbox.  Sending systems SHOULD not generate the optional list of
        hosts known as a source route.

        .......

        "RCPT TO:" ("<Postmaster@" domain ">" / "<Postmaster>" / Forward-Path)
                                        [SP Rcpt-parameters] CRLF

        -- RFC 2821, 4.2.2.

            250 Requested mail action okay, completed
            251 User not local; will forward to <forward-path>
         (See section 3.4)
            252 Cannot VRFY user, but will accept message and attempt
                    delivery

        -- RFC 2821, 4.3.2.

        RCPT
            S: 250, 251 (but see section 3.4 for discussion of 251 and 551)
            E: 550, 551, 552, 553, 450, 451, 452, 503, 550
        */

        //We'll treat 252 as accepted since it isn't really a failure

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
            ->with("MAIL FROM:<me@domain.com>\r\n")
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn('250 OK'."\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with("RCPT TO:<foo@bar>\r\n")
            ->andReturn(2);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(2)
            ->andReturn('250 OK'."\r\n");

        $this->finishBuffer($buf);
        try {
            $smtp->start();
            $smtp->send($message);
        } catch (Exception $e) {
            $this->fail('RCPT TO should accept a 250 response');
        }
    }

    public function testUtf8AddressWithIdnEncoder()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $message = $this->createMessage();

        $message->shouldReceive('getFrom')
                ->once()
                ->andReturn(['me@dömain.com' => 'Me']);
        $message->shouldReceive('getTo')
                ->once()
                ->andReturn(['foo@bär' => null]);
        $buf->shouldReceive('write')
            ->once()
            ->with("MAIL FROM:<me@xn--dmain-jua.com>\r\n")
            ->andReturn(1);
        $buf->shouldReceive('write')
            ->once()
            ->with("RCPT TO:<foo@xn--br-via>\r\n")
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn('250 OK'."\r\n");

        $this->finishBuffer($buf);
        $smtp->start();
        $smtp->send($message);
    }

    public function testUtf8AddressWithUtf8Encoder()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf, null, new Swift_AddressEncoder_Utf8AddressEncoder());
        $message = $this->createMessage();

        $message->shouldReceive('getFrom')
                ->once()
                ->andReturn(['më@dömain.com' => 'Me']);
        $message->shouldReceive('getTo')
                ->once()
                ->andReturn(['föö@bär' => null]);
        $buf->shouldReceive('write')
            ->once()
            ->with("MAIL FROM:<më@dömain.com>\r\n")
            ->andReturn(1);
        $buf->shouldReceive('write')
            ->once()
            ->with("RCPT TO:<föö@bär>\r\n")
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn('250 OK'."\r\n");

        $this->finishBuffer($buf);
        $smtp->start();
        $smtp->send($message);
    }

    public function testNonEncodableSenderCausesException()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $message = $this->createMessage();

        $message->shouldReceive('getFrom')
                ->once()
                ->andReturn(['më@domain.com' => 'Me']);
        $message->shouldReceive('getTo')
                ->once()
                ->andReturn(['foo@bar' => null]);

        $this->finishBuffer($buf);
        try {
            $smtp->start();
            $smtp->send($message);
            $this->fail('më@domain.com cannot be encoded (not observed)');
        } catch (Swift_AddressEncoderException $e) {
            $this->assertEquals('më@domain.com', $e->getAddress());
        }
    }

    public function testMailFromCommandIsOnlySentOncePerMessage()
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
            ->with("MAIL FROM:<me@domain.com>\r\n")
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn('250 OK'."\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with("RCPT TO:<foo@bar>\r\n")
            ->andReturn(2);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(2)
            ->andReturn('250 OK'."\r\n");
        $buf->shouldReceive('write')
            ->never()
            ->with("MAIL FROM:<me@domain.com>\r\n");

        $this->finishBuffer($buf);
        $smtp->start();
        $smtp->send($message);
    }

    public function testMultipleRecipientsSendsMultipleRcpt()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $message = $this->createMessage();

        $message->shouldReceive('getFrom')
                ->once()
                ->andReturn(['me@domain.com' => 'Me']);
        $message->shouldReceive('getTo')
                ->once()
                ->andReturn([
                    'foo@bar' => null,
                    'zip@button' => 'Zip Button',
                    'test@domain' => 'Test user',
                    'tëst@domain' => 'Test user',
                ]);
        $buf->shouldReceive('write')
            ->once()
            ->with("RCPT TO:<foo@bar>\r\n")
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn('250 OK'."\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with("RCPT TO:<zip@button>\r\n")
            ->andReturn(2);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(2)
            ->andReturn('250 OK'."\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with("RCPT TO:<test@domain>\r\n")
            ->andReturn(3);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(3)
            ->andReturn('250 OK'."\r\n");

        $this->finishBuffer($buf);
        $smtp->start();
        $smtp->send($message);
    }

    public function testCcRecipientsSendsMultipleRcpt()
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
        $message->shouldReceive('getCc')
                ->once()
                ->andReturn([
                    'zip@button' => 'Zip Button',
                    'test@domain' => 'Test user',
                ]);
        $buf->shouldReceive('write')
            ->once()
            ->with("RCPT TO:<foo@bar>\r\n")
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn('250 OK'."\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with("RCPT TO:<zip@button>\r\n")
            ->andReturn(2);
        $buf->shouldReceive('readLine')
      