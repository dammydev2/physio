'dispatchEvent')
                   ->zeroOrMoreTimes();
        $evt->shouldReceive('bubbleCancelled')
            ->atLeast()->once()
            ->andReturn(false);

        $this->finishBuffer($buf);
        $smtp->start();
    }

    public function testStartingTransportDispatchesBeforeTransportChangeEvent()
    {
        $buf = $this->getBuffer();
        $dispatcher = $this->createEventDispatcher(false);
        $evt = $this->getMockery('Swift_Events_TransportChangeEvent');
        $smtp = $this->getTransport($buf, $dispatcher);

        $dispatcher->shouldReceive('createTransportChangeEvent')
                   ->atLeast()->once()
                   ->with($smtp)
                   ->andReturn($evt);
        $dispatcher->shouldReceive('dispatchEvent')
                   ->once()
                   ->with($evt, 'beforeTransportStarted');
        $dispatcher->shouldReceive('dispatchEvent')
                   ->zeroOrMoreTimes();
        $evt->shouldReceive('bubbleCancelled')
            ->atLeast()->once()
            ->andReturn(false);

        $this->finishBuffer($buf);
        $smtp->start();
    }

    public function testCancellingBubbleBeforeTransportStartStopsEvent()
    {
        $buf = $this->getBuffer();
        $dispatcher = $this->createEventDispatcher(false);
        $evt = $this->getMockery('Swift_Events_TransportChangeEvent');
        $smtp = $this->getTransport($buf, $dispatcher);

        $dispatcher->shouldReceive('createTransportChangeEvent')
                   ->atLeast()->once()
                   ->with($smtp)
                   ->andReturn($evt);
        $dispatcher->shouldReceive('dispatchEvent')
                   ->once()
                   ->with($evt, 'beforeTransportStarted');
        $dispatcher->shouldReceive('dispatchEvent')
                   ->zeroOrMoreTimes();
        $evt->shouldReceive('bubbleCancelled')
            ->atLeast()->once()
            ->andReturn(true);

        $this->finishBuffer($buf);
        $smtp->start();

        $this->assertFalse($smtp->isStarted(),
            '%s: Transport should not be started since event bubble was cancelled'
        );
    }

    public function testStoppingTransportDispatchesTransportChangeEvent()
    {
        $buf = $this->getBuffer();
        $dispatcher = $this->createEventDispatcher(false);
        $evt = $this->getMockery('Swift_Events_TransportChangeEvent')->shouldIgnoreMissing();
        $smtp = $this->getTransport($buf, $dispatcher);

        $dispatcher->shouldReceive('createTransportChangeEvent')
                   ->atLeast()->once()
                   ->with($smtp)
                   ->andReturn($evt);
        $dispatcher->shouldReceive('dispatchEvent')
                   ->once()
                   ->with($evt, 'transportStopped');
        $dispatcher->shouldReceive('dispatchEvent')
                   ->zeroOrMoreTimes();

        $this->finishBuffer($buf);
        $smtp->start();
        $smtp->stop();
    }

    public function testStoppingTransportDispatchesBeforeTransportChangeEvent()
    {
        $buf = $this->getBuffer();
        $dispatcher = $this->createEventDispatcher(false);
        $evt = $this->getMockery('Swift_Events_TransportChangeEvent')->shouldIgnoreMissing();
        $smtp = $this->getTransport($buf, $dispatcher);

        $dispatcher->shouldReceive('createTransportChangeEvent')
                   ->atLeast()->once()
                   ->with($smtp)
                   ->andReturn($evt);
        $dispatcher->shouldReceive('dispatchEvent')
                   ->once()
                   ->with($evt, 'beforeTransportStopped');
        $dispatcher->shouldReceive('dispatchEvent')
                   ->zeroOrMoreTimes();

        $this->finishBuffer($buf);
        $smtp->start();
        $smtp->stop();
    }

    public function testCancellingBubbleBeforeTransportStoppedStopsEvent()
    {
        $buf = $this->getBuffer();
        $dispatcher = $this->createEventDispatcher(false);
        $evt = $this->getMockery('Swift_Events_TransportChangeEvent');
        $smtp = $this->getTransport($buf, $dispatcher);

        $hasRun = false;
        $dispatcher->shouldReceive('createTransportChangeEvent')
                   ->atLeast()->once()
                   ->with($smtp)
                   ->andReturn($evt);
        $dispatcher->shouldReceive('dispatchEvent')
                   ->once()
                   ->with($evt, 'beforeTransportStopped')
                   ->andReturnUsing(function () use (&$hasRun) {
                       $hasRun = true;
                   });
        $dispatcher->shouldReceive('dispatchEvent')
                   ->zeroOrMoreTimes();
        $evt->shouldReceive('bubbleCancelled')
            ->zeroOrMoreTimes()
            ->andReturnUsing(function () use (&$hasRun) {
                return $hasRun;
            });

        $this->finishBuffer($buf);
        $smtp->start();
        $smtp->stop();

        $this->assertTrue($smtp->isStarted(),
            '%s: Transport should not be stopped since event bubble was cancelled'
        );
    }

    public function testResponseEventsAreGenerated()
    {
        $buf = $this->getBuffer();
        $dispatcher = $this->createEventDispatcher(false);
        $evt = $this->getMockery('Swift_Events_ResponseEvent');
        $smtp = $this->getTransport($buf, $dispatcher);

        $dispatcher->shouldReceive('createResponseEvent')
                   ->atLeast()->once()
                   ->with($smtp, \Mockery::any(), \Mockery::any())
                   ->andReturn($evt);
        $dispatcher->shouldReceive('dispatchEvent')
                   ->atLeast()->once()
                   ->with($evt, 'responseReceived');

        $this->finishBuffer($buf);
        $smtp->start();
    }

    public function testCommandEventsAreGenerated()
    {
        $buf = $this->getBuffer();
        $dispatcher = $this->createEventDispatcher(false);
        $evt = $this->getMockery('Swift_Events_CommandEvent');
        $smtp = $this->getTransport($buf, $dispatcher);

        $dispatcher->shouldReceive('createCommandEvent')
                   ->once()
                   ->with($smtp, \Mockery::any(), \Mockery::any())
                   ->andReturn($evt);
        $dispatcher->shouldReceive('dispatchEvent')
                   ->once()
                   ->with($evt, 'commandSent');

        $this->finishBuffer($buf);
        $smtp->start();
    }

    public function testExceptionsCauseExceptionEvents()
    {
        $buf = $this->getBuffer();
        $dispatcher = $this->createEventDispatcher(false);
        $evt = $this->getMockery('Swift_Events_TransportExceptionEvent');
        $smtp = $this->getTransport($buf, $dispatcher);

        $buf->shouldReceive('readLine')
            ->atLeast()->once()
            ->andReturn("503 I'm sleepy, go away!\r\n");
        $dispatcher->shouldReceive('createTransportExceptionEvent')
                   ->zeroOrMoreTimes()
                   ->with($smtp, \Mockery::any())
                   ->andReturn($evt);
        $dispatcher->shouldReceive('dispatchEvent')
                   ->once()
                   ->with($evt, 'exceptionThrown');
        $evt->shouldReceive('bubbleCancelled')
            ->atLeast()->once()
            ->andReturn(false);

        try {
            $smtp->start();
            $this->fail('TransportException should be thrown on invalid response');
        } catch (Swift_TransportException $e) {
        }
    }

    public function testExceptionBubblesCanBeCancelled()
    {
        $buf = $this->getBuffer();
        $dispatcher = $this->createEventDispatcher(false);
        $evt = $this->getMockery('Swift_Events_TransportExceptionEvent');
        $smtp = $this->getTransport($buf, $dispatcher);

        $buf->shouldReceive('readLine')
            ->atLeast()->once()
            ->andReturn("503 I'm sleepy, go away!\r\n");
        $dispatcher->shouldReceive('createTransportExceptionEvent')
                   ->twice()
                   ->with($smtp, \Mockery::any())
                   ->andReturn($evt);
        $dispatcher->shouldReceive('dispatchEvent')
                   ->twice()
                   ->with($evt, 'exceptionThrown');
        $evt->shouldReceive('bubbleCancelled')
            ->atLeast()->once()
            ->andReturn(true);

        $this->finishBuffer($buf);
        $smtp->start();
    }

    protected function createEventDispatcher($stub = true)
    {
        return $this->getMockery('Swift_Events_EventDispatcher')->shouldIgnoreMissing();
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <?php

abstract class Swift_Transport_AbstractSmtpTest extends \SwiftMailerTestCase
{
    abstract protected function getTransport($buf);

    public function testStartAccepts220ServiceGreeting()
    {
        /* -- RFC 2821, 4.2.

     Greeting = "220 " Domain [ SP text ] CRLF

     -- RFC 2822, 4.3.2.

     CONNECTION ESTABLISHMENT
         S: 220
         E: 554
        */

        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $buf->shouldReceive('initialize')
            ->once();
        $buf->shouldReceive('readLine')
            ->once()
            ->with(0)
            ->andReturn("220 some.server.tld bleh\r\n");

        $this->finishBuffer($buf);
        try {
            $this->assertFalse($smtp->isStarted(), '%s: SMTP should begin non-started');
            $smtp->start();
            $this->assertTrue($smtp->isStarted(), '%s: start() should have started connection');
        } catch (Exception $e) {
            $this->fail('220 is a valid SMTP greeting and should be accepted');
        }
    }

    public function testBadGreetingCausesException()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $buf->shouldReceive('initialize')
            ->once();
        $buf->shouldReceive('readLine')
            ->once()
            ->with(0)
            ->andReturn("554 I'm busy\r\n");
        $this->finishBuffer($buf);
        try {
            $this->assertFalse($smtp->isStarted(), '%s: SMTP should begin non-started');
            $smtp->start();
            $this->fail('554 greeting indicates an error and should cause an exception');
        } catch (Swift_TransportException $e) {
            $this->assertFalse($smtp->isStarted(), '%s: start() should have failed');
        }
    }

    public function testStartSendsHeloToInitiate()
    {
        /* -- RFC 2821, 3.2.

            3.2 Client Initiation

         Once the server has sent the welcoming message and the client has
         received it, the client normally sends the EHLO command to the
         server, indicating the client's identity.  In addition to opening the
         session, use of EHLO indicates that the client is able to process
         service extensions and requests that the server provide a list of the
         extensions it supports.  Older SMTP systems which are unable to
         support service extensions and contemporary clients which do not
         require service extensions in the mail session being initiated, MAY
         use HELO instead of EHLO.  Servers MUST NOT return the extended
         EHLO-style response to a HELO command.  For a particular connection
         attempt, if the server returns a "command not recognized" response to
         EHLO, the client SHOULD be able to fall back and send HELO.

         In the EHLO command the host sending the command identifies itself;
         the command may be interpreted as saying "Hello, I am <domain>" (and,
         in the case of EHLO, "and I support service extension requests").

       -- RFC 2281, 4.1.1.1.

       ehlo            = "EHLO" SP Domain CRLF
       helo            = "HELO" SP Domain CRLF

       -- RFC 2821, 4.3.2.

       EHLO or HELO
           S: 250
           E: 504, 550

     */

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
            ->with('~^HELO example.org\r\n$~D')
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn('250 ServerName'."\r\n");

        $this->finishBuffer($buf);
        try {
            $smtp->start();
        } catch (Exception $e) {
            $this->fail('Starting SMTP should send HELO and accept 250 response');
        }
    }

    public function testInvalidHeloResponseCausesException()
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
            ->with('~^HELO example.org\r\n$~D')
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn('504 WTF'."\r\n");

        $this->finishBuffer($buf);
        try {
            $this->assertFalse($smtp->isStarted(), '%s: SMTP should begin non-started');
            $smtp->start();
            $this->fail('Non 250 HELO response should raise Exception');
        } catch (Swift_TransportException $e) {
            $this->assertFalse($smtp->isStarted(), '%s: SMTP start() should have failed');
        }
    }

    public function testDomainNameIsPlacedInHelo()
    {
        /* -- RFC 2821, 4.1.4.

       The SMTP client MUST, if possible, ensure that the domain parameter
       to the EHLO command is a valid principal host name (not a CNAME or MX
       name) for its host.  If this is not possible (e.g., when the client's
       address is dynamically assigned and the client does not have an
       obvious name), an address literal SHOULD be substituted for the
       domain name and supplemental information provided that will assist in
       identifying the client.
        */

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
            ->with("HELO mydomain.com\r\n")
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn('250 ServerName'."\r\n");

        $this->finishBuffer($buf);
        $smtp->setLocalDomain('mydomain.com');
        $smtp->start();
    }

    public function testSuccessfulMailCommand()
    {
        /* -- RFC 2821, 3.3.

        There are three steps to SMTP mail transactions.  The transaction
        starts with a MAIL command which gives the sender identification.

        .....

        The first step in the procedure is the MAIL command.

            MAIL FROM:<reverse-path> [SP <mail-parameters> ] <CRLF>

        -- RFC 2821, 4.1.1.2.

        Syntax:

            "MAIL FROM:" ("<>" / Reverse-Path)
                       [SP Mail-parameters] CRLF
        -- RFC 2821, 4.1.2.

        Reverse-path = Path
            Forward-path = Path
            Path = "<" [ A-d-l ":" ] Mailbox ">"
            A-d-l = At-domain *( "," A-d-l )
                        ; Note that this form, the so-called "source route",
                        ; MUST BE accepted, SHOULD NOT be generated, and SHOULD be
                        ; ignored.
            At-domain = "@" domain

        -- RFC 2821, 4.3.2.

        MAIL
            S: 250
            E: 552, 451, 452, 550, 553, 503
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
        $buf->shouldReceive('initialize')
            ->once();
        $buf->shouldReceive('write')
            ->once()
            ->with("MAIL FROM:<me@domain.com>\r\n")
            ->andReturn(1);
        $buf->shouldReceive('readLine')
            ->once()
            ->with(1)
            ->andReturn("250 OK\r\n");

        $this->finishBuffer($buf);
        try {
            $smtp->start();
            $smtp->send($message);
        } catch (Exception $e) {
            $this->fail('MAIL FROM should accept a 250 response');
        }
    }

    public function testInvalidResponseCodeFromMailCausesException()
   