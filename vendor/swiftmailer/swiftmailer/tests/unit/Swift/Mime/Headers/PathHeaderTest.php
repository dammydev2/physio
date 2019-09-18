m\r\n");

        $smtp->start();

        $this->expectException('Swift_TransportException');
        $this->expectExceptionMessage('Expected response code 250 but got code "550"');
        $smtp->send($message, $failedRecipients);
    }

    public function testPipeliningWithDataFailure()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $this->assertNull($smtp->getPipelining());

        $message = $this->createMessage();
        $message->shouldReceive('getFrom')
                ->zeroOrMoreTimes()
                ->andReturn(['me@domain.com' => 'Me']);
        $message->shouldReceive('getTo')
                ->zeroOrMoreTimes()
                ->andReturn(['foo@bar' => null]);

        $buf->shouldReceive('initialize')
            ->once();
        $buf->shouldReceive('readLine')
            ->once()
            ->with(0)
            ->andReturn("220 some.server.tld bleh\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with('~^EHLO .+?\r\n$~D')
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn('250-ServerName'."\r\n");
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn('250 PIPELINING'."\r\n");

        $buf->shouldReceive('write')
            ->ordered()
            ->once()
            ->with("MAIL FROM:<me@domain.com>\r\n")
            ->andReturn(1);
        $buf->shouldReceive('write')
            ->ordered()
            ->once()
            ->with("RCPT TO:<foo@bar>\r\n")
            ->andReturn(2);
        $buf->shouldReceive('write')
            ->ordered()
            ->once()
            ->with("DATA\r\n")->andReturn(3);
        $buf->shouldReceive('readLine')
            ->ordered()
            ->once()
            ->with(1)
            ->andReturn("250 OK\r\n");
        $buf->shouldReceive('readLine')
            ->ordered()
            ->once()
            ->with(2)
            ->andReturn("250 OK\r\n");
        $buf->shouldReceive('readLine')
            ->ordered()
            ->once()
            ->with(3)
            ->andReturn("452 Insufficient system storage\r\n");

        $smtp->start();

        $this->expectException('Swift_TransportException');
        $this->expectExceptionMessage('Expected response code 354 but got code "452"');
        $smtp->send($message, $failedRecipients);
    }

    public function providerPipeliningOverride()
    {
        return [
            [null, true, true],
            [null, false, false],
            [true, false, true],
            [true, true, true],
            [false, false, false],
            [false, true, false],
        ];
    }

    /**
     * @dataProvider providerPipeliningOverride
     */
    public function testPipeliningOverride($enabled, bool $supported, bool $expected)
    {
        $buf = $this->getBuffer();
        $s