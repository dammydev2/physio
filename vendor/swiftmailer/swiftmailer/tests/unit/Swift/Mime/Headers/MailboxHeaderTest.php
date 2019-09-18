
        /* -- RFC 2821, 4.1.1.10.

        This command specifies that the receiver MUST send an OK reply, and
        then close the transmission channel.

        The receiver MUST NOT intentionally close the transmission channel
        until it receives and replies to a QUIT command (even if there was an
        error).  The sender MUST NOT intentionally close the transmission
        channel until it sends a QUIT command and SHOULD wait until it
        receives the reply (even if there was an error response to a previous
        command).  If the connection is closed prematurely due to violations
        of the above or system or network failure, the server MUST cancel any
        pending transaction, but not undo any previously completed
        transaction, and generally MUST act as if the command or transaction
        in progress had received a temporary error (i.e., a 4yz response).

        The QUIT command may be issued at any time.

        Syntax:
            "QUIT" CRLF
        */

        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $message = $this->createMessage();
        $buf->shouldReceive('initialize')
            ->once();
        $buf->shouldReceive('write')
            ->once()
            ->with("QUIT\r\n")
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn("221 Bye\r\n");
        $buf->shouldReceive('terminate')
            ->once();

        $this->finishBuffer($buf);

        $this->assertFalse($smtp->isStarted());
        $smtp->start();
        $this->assertTrue($smtp->isStarted());
        $smtp->stop();
        $this->assertFalse($smtp->isStarted());
    }

    public function testBufferCanBeFetched()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $ref = $smtp->getBuffer();
        $this->assertEquals($buf, $ref);
    }

    public function testBufferCanBeWrittenToUsingExecuteCommand()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $message = $this->createMessage();
        $buf->shouldReceive('write')
            ->zeroOrMoreTimes()
            ->with("FOO\r\n")
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->zeroOrMoreTimes()
            ->with(1)
            ->andReturn("250 OK\r\n");

        $res = $smtp->executeCommand("FOO\r\n");
        $this->assertEquals("250 OK\r\n", $res);
    }

    public function testResponseCodesAreValidated()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $message = $this->createMessage();
        $buf->shouldReceive('write')
            ->zeroOrMoreTimes()
            ->with("FOO\r\n")
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->zeroOrMoreTimes()
            ->with(1)
            ->andReturn("551 Not ok\r\n");

        try {
            $smtp->executeCommand("FOO\r\n", [250, 251]);
            $this->fail('A 250 or 251 response was needed but 551 was returned.');
        } catch (Swift_TransportException $e) {
        }
    }

    public function testFailedRecipientsCanBeCollectedByReference()
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
        $buf->shouldReceive('readLine')->once()->with(6)->andReturn("500 Bad\r\n");
        $buf->shouldReceive('write')->once()->with("RSET\r\n")->andReturn(7);
        $buf->shouldReceive('readLine')->once()->with(7)->andReturn("250 OK\r\n");

        $buf->shouldReceive('write')->once()->with("MAIL FROM:<me@domain.com>\r\n")->andReturn(9);
        $buf->shouldReceive('readLine')->once()->with(9)->andReturn("250 OK\r\n");
        $buf->shouldReceive('write')->once()->with("RCPT TO:<test@domain>\r\n")->andReturn(10);
        $buf->shouldReceive('readLine')->once()->with(10)->andReturn("500 Bad\r\n");
        $buf->shouldReceive('write')->once()->with("RSET\r\n")->andReturn(11);
        $buf->shouldReceive('readLine')->once()->with(11)->andReturn("250 OK\r\n");

        $this->finishBuffer($buf);
        $smtp->start();
        $this->assertEquals(1, $smtp->send($message, $failures));
        $this->assertEquals(['zip@button', 'test@domain'], $failures,
            '%s: Failures should be caught in an array'
            );
    }

    public function testSendingRegeneratesMessageId()
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
        $message->shouldReceive('generateId')
                ->once();

        $this->finishBuffer($buf);
        $smtp->start();
        $smtp->send($message);
    }

    public function testPing()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);

        $buf->shouldReceive('initialize')
            ->once();
        $buf->shouldReceive('readLine')
            ->once()
            ->with(0)
            ->andReturn("220 some.server.tld bleh\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with('~^NOOP\r\n$~D')
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn('250 OK'."\r\n");

        $this->finishBuffer($buf);
        $this->assertTrue($smtp->ping());
    }

    public function testPingOnDeadConnection()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);

        $buf->shouldReceive('initialize')
            ->once();
        $buf->shouldReceive('readLine')
            ->once()
            ->with(0)
            ->andReturn("220 some.server.tld bleh\r\n");
        $buf->shouldReceive('write')
            ->once()
            ->with('~^NOOP\r\n$~D')
            ->andThrow('Swift_TransportException');

        $this->finishBuffer($buf);
        $smtp->start();
        $this->assertTrue($smtp->isStarted());
        $this->assertFalse($smtp->ping());
        $this->assertFalse($smtp->isStarted());
    }

    public function testSetLocalDomain()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);

        $smtp->setLocalDomain('example.com');
        $this->assertEquals('example.com', $smtp->getLocalDomain());

        $smtp->setLocalDomain('192.168.0.1');
        $this->assertEquals('[192.168.0.1]', $smtp->getLocalDomain());

        $smtp->setLocalDomain('[192.168.0.1]');
        $this->assertEquals('[192.168.0.1]', $smtp->getLocalDomain());

        $smtp->setLocalDomain('fd00::');
        $this->assertEquals('[IPv6:fd00::]', $smtp->getLocalDomain());

        $smtp->setLocalDomain('[IPv6:fd00::]');
        $this->assertEquals('[IPv6:fd00::]', $smtp->getLocalDomain());
    }

    protected function getBuffer()
    {
        return $this->getMockery('Swift_Transport_IoBuffer')->shouldIgnoreMissing();
    }

    protected function createMessage()
    {
        return $this->getMockery('Swift_Mime_SimpleMessage')->shouldIgnoreMissing();
    }

    protected function finishBuffer($buf)
    {
        $buf->shouldReceive('readLine')
            ->zeroOrMoreTimes()
            ->with(0)
            ->andReturn('220 server.com foo'."\r\n");
        $buf->shouldReceive('write')
            ->zeroOrMoreTimes()
            ->with('~^(EH|HE)LO .*?\r\n$~D')
            ->andReturn($x = uniqid('', true));
        $buf->shouldReceive('readLine')
            ->zeroOrMoreTimes()
            ->with($x)
            ->andReturn('250 ServerName'."\r\n");
        $buf->shouldReceive('write')
            ->zeroOrMoreTimes()
            ->with('~^MAIL FROM:<.*?>\r\n$~D')
            ->andReturn($x = uniqid('', true));
        $buf->shouldReceive('readLine')
            ->zeroOrMoreTimes()
            ->with($x)
            ->andReturn("250 OK\r\n");
        $buf->shouldReceive('write')
            ->zeroOrMoreTimes()
            ->with('~^RCPT TO:<.*?>\r\n$~D')
            ->andReturn($x = uniqid('', true));
        $buf->shouldReceive('readLine')
            ->zeroOrMoreTimes()
            ->with($x)
            ->andReturn("250 OK\r\n");
        $buf->shouldReceive('write')
            ->zeroOrMoreTimes()
            ->with("DATA\r\n")
            ->andReturn($x = uniqid('', true));
        $buf->shouldReceive('readLine')
            ->zeroOrMoreTimes()
            ->with($x)
            ->andReturn("354 OK\r\n");
        $buf->shouldReceive('write')
            ->zeroOrMoreTimes()
            ->with("\r\n.\r\n")
            ->andReturn($x = uniqid('', true));
        $buf->shouldReceive('readLine')
            ->zeroOrMoreTimes()
            ->with($x)
            ->andReturn("250 OK\r\n");
        $buf->shouldReceive('write')
            ->zeroOrMoreTimes()
            ->with("RSET\r\n")
            ->andReturn($x = uniqid('', true));
        $buf->shouldReceive('readLine')
            ->zeroOrMoreTimes()
            ->with($x)
            ->andReturn("250 OK\r\n");

        $buf->shouldReceive('write')
            ->zeroOrMoreTimes()
            ->andReturn(false);
        $buf->shouldReceive('readLine')
            ->zeroOrMoreTimes()
            ->andReturn(false);
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         