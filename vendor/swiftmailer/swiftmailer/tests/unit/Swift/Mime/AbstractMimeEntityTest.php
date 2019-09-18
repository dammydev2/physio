<?php

class Swift_Mime_Headers_UnstructuredHeaderTest extends \SwiftMailerTestCase
{
    private $charset = 'utf-8';

    public function testTypeIsTextHeader()
    {
        $header = $this->getHeader('Subject', $this->getEncoder('Q', true));
        $this->assertEquals(Swift_Mime_Header::TYPE_TEXT, $header->getFieldType());
    }

    public function testGetNameReturnsNameVerbatim()
    {
        $header = $this->getHeader('Subject', $this->getEncoder('Q', true));
        $this->assertEquals('Subject', $header->getFieldName());
    }

    public function testGetValueReturnsValueVerbatim()
    {
        $header = $this->getHeader('Subject', $this->getEncoder('Q', true));
        $header->setValue('Test');
        $this->assertEquals('Test', $header->getValue());
    }

    public function testBasicStructureIsKeyValuePair()
    {
        /* -- RFC 2822, 2.2
        Header fields are lines composed of a field name, followed by a colon
        (":"), followed by a field body, and terminated by CRLF.
        */
        $header = $this->getHeader('Subject', $this->getEncoder('Q', true));
        $header->setValue('Test');
        $this->assertEquals('Subject: Test'."\r\n", $header->toString());
    }

    public function testLongHeadersAreFoldedAtWordBoundary()
    {
        /* -- RFC 2822, 2.2.3
        Each header field is logically a single line of characters comprising
        the field name, the colon, and the field body.  For convenience
        however, and to deal with the 998/78 character limitations per line,
        the field body portion of a header field can be split into a multiple
        line representation; this is called "folding".  The general rule is
        that wherever this standard allows for folding white space (not
        simply WSP characters), a CRLF may be inserted before any WSP.
        */

        $value = 'The quick brown fox jumped over the fence, he was a very very '.
            'scary brown fox with a bushy tail';
        $header = $this->getHeader('X-Custom-Header',
            $this->getEncoder('Q', true)
            );
        $header->setValue($value);
        $header->setMaxLineLength(78); //A safe [RFC 2822, 2.2.3] default
        /*
        X-Custom-Header: The quick brown fox jumped over the fence, he was a very very
     scary brown fox with a bushy tail
        */
        $this->assertEquals(
            'X-Custom-Header: The quick brown fox jumped over the fence, he was a'.
            ' very very'."\r\n".//Folding
            ' scary brown fox with a bushy tail'."\r\n",
            $header->toString(), '%s: The header should have been folded at 78th char'
            );
    }

    public function testPrintableAsciiOnlyAppearsInHeaders()
    {
        /* -- RFC 2822, 2.2.
        A field name MUST be composed of printable US-ASCII characters (i.e.,
        characters that have values between 33 and 126, inclusive), except
        colon.  A field body may be composed of any US-ASCII characters,
        except for CR and LF.
        */

        $nonAsciiChar = pack('C', 0x8F);
        $header = $this->getHeader('X-Test', $this->getEncoder('Q', true));
        $header->setValue($nonAsciiChar);
        $this->assertRegExp(
            '~^[^:\x00-\x20\x80-\xFF]+: [^\x80-\xFF\r\n]+\r\n$~s',
            $header->toString()
            );
    }

    public function testEncodedWordsFollowGeneralStructure()
    {
        /* -- RFC 2047, 1.
        Generally, an "encoded-word" is a sequence of printable ASCII
        characters that begins with "=?", ends with "?=", and has two "?"s in
        between.
        */

        $nonAsciiChar = pack('C', 0x8F);
        $header = $this->getHeader('X-Test', $this->getEncoder('Q', true));
        $header->setValue($nonAsciiChar);
        $this->assertRegExp(
            '~^X-Test: \=?.*?\?.*?\?.*?\?=\r\n$~s',
            $header->toString()
            );
    }

    public function testEncodedWordIncludesCharsetAndEncodingMethodAndText()
    {
        /* -- RFC 2047, 2.
        An 'encoded-word' is defined by the following ABNF grammar.  The
        notation of RFC 822 is used, with the exception that white space
        characters MUST NOT appear between components of an 'encoded-word'.

        encoded-word = "=?" charset "?" encoding "?" encoded-text "?="
        */

        $nonAsciiChar = pack('C', 0x8F);

        $encoder = $this->getEncoder('Q');
        $encoder->shouldReceive('encodeString')
                ->once()
                ->with($nonAsciiChar, \Mockery::any(), \Mockery::any(), \Mockery::any())
                ->andReturn('=8F');

        $header = $this->getHeader('X-Test', $encoder);
        $header->setValue($nonAsciiChar);
        $this->assertEquals(
            'X-Test: =?'.$this->charset.'?Q?=8F?='."\r\n",
            $header->toString()
            );
    }

    public function testEncodedWordsAreUsedToEncodedNonPrintableAscii()
    {
        //SPACE and TAB permitted
        $nonPrintableBytes = array_merge(
            range(0x00, 0x08), range(0x10, 0x19), [0x7F]
            );

        foreach ($nonPrintableBytes as $byte) {
            $char = pack('C', $byte);
            $encodedChar = sprintf('=%02X', $byte);

            $encoder = $this->getEncoder('Q');
            $encoder->shouldReceive('encodeString')
                ->once()
                ->with($char, \Mockery::any(), \Mockery::any(), \Mockery::any())
                ->andReturn($encodedChar);

            $header = $this->getHeader('X-A', $encoder);
            $header->setValue($char);

            $this->assertEquals(
                'X-A: =?'.$this->charset.'?Q?'.$encodedChar.'?='."\r\n",
                $header->toString(), '%s: Non-printable ascii should be encoded'
                );
        }
    }

    public function testEncodedWordsAreUsedToEncode8BitOctets()
    {
        foreach (range(0x80, 0xFF) as $byte) {
            $char = pack('C', $byte);
            $encodedChar = sprintf('=%02X', $byte);

            $encoder = $this->getEncoder('Q');
            $encoder->shouldReceive('encodeString')
                ->once()
                ->with($char, \Mockery::any(), \Mockery::any(), \Mockery::any())
                ->andReturn($encodedChar);

            $header = $this->getHeader('X-A', $encoder);
            $header->setValue($char);

            $this->assertEquals(
                'X-A: =?'.$this->charset.'?Q?'.$encodedChar.'?='."\r\n",
                $header->toString(), '%s: 8-bit octets should be encoded'
                );
        }
    }

    public function testEncodedWordsAreNoMoreThan75CharsPerLine()
    {
        /* -- RFC 2047, 2.
        An 'encoded-word' may not be more than 75 characters long, including
        'charset', 'encoding', 'encoded-text', and delimiters.

        ... SNIP ...

        While there is no limit to the length of a multiple-line header
        field, each line of a header field that contains one or more
        'encoded-word's is limited to 76 characters.
        */

        $nonAsciiChar = pack('C', 0x8F);

        $encoder = $this->getEncoder('Q');
        $encoder->shouldReceive('encodeString')
                ->once()
                ->with($nonAsciiChar, \Mockery::any(), \Mockery::any(), \Mockery::any())
                ->andReturn('=8F');
        //Note that multi-line headers begin with LWSP which makes 75 + 1 = 76
        //Note also that =?utf-8?q??= is 12 chars which makes 75 - 12 = 63

        //* X-Test: is 8 chars
        $header = $this->getHeader('X-Test', $encoder);
        $header->setValue($nonAsciiChar);

        $this->assertEquals(
            'X-Test: =?'.$this->charset.'?Q?=8F?='."\r\n",
            $header->toString()
            );
    }

    public function testFWSPIsUsedWhenEncoderReturnsMultipleLines()
    {
        /* --RFC 2047, 2.
        If it is desirable to encode more text than will fit in an 'encoded-word' of
        75 characters, multiple 'encoded-word's (separated by CRLF SPACE) may
        be used.
        */

        //Note the Mock does NOT return 8F encoded, the 8F merely triggers
        // encoding for the sake of testing
        $nonAsciiChar = pack('C', 0x8F);

        $encoder = $this->getEncoder('Q');
        $encoder->shouldReceive('encodeString')
                ->once()
                ->with($nonAsciiChar, 8, 63, \Mockery::any())
                ->andReturn('line_one_here'."\r\n".'line_two_here');

        //Note that multi-line headers begin with LWSP which makes 75 + 1 = 76
        //Note also that =?utf-8?q??= is 12 chars which makes 75 - 12 = 63

        //* X-Test: is 8 chars
        $header = $this->getHeader('X-Test', $encoder);
        $header->setValue($nonAsciiChar);

        $this->assertEquals(
            'X-Test: =?'.$this->charset.'?Q?line_one_here?='."\r\n".
            ' =?'.$this->charset.'?Q?line_two_here?='."\r\n",
            $header->toString()
            );
    }

    public function testAdjacentWordsAreEncodedTogether()
    {
        /* -- RFC 2047, 5 (1)
     Ordinary ASCII text and 'encoded-word's may appear together in the
     same header field.  However, an 'encoded-word' that appears in a
     header field defined as '*text' MUST be separated from any adjacent
     'encoded-word' or 'text' by 'linear-white-space'.

     -- RFC 2047, 2.
     IMPORTANT: 'encoded-word's are designed to be recognized as 'atom's
     by an RFC 822 parser.  As a consequence, unencoded white space
     characters (such as SPACE and HTAB) are FORBIDDEN within an
     'encoded-word'.
     */

        //It would be valid to encode all words needed, however it's probably
        // easiest to encode the longest amount required at a time

        $word = 'w'.pack('C', 0x8F).'rd';
        $text = 'start '.$word.' '.$word.' then end '.$word;
        // 'start', ' word word', ' and end', ' word'

        $encoder = $this->getEncoder('Q');
        $encoder->shouldReceive('encodeString')
                ->once()
                ->with($word.' '.$word, \Mockery::any(), \Mockery::any(), \Mockery::any())
                ->andReturn('w=8Frd_w=8Frd');
        $encoder->shouldReceive('encodeString')
                ->once()
                ->with($word, \Mockery::any(), \Mockery::any(), \Mockery::any())
                ->andReturn('w=8Frd');

        $header = $this->getHeader('X-Test', $encoder);
        $header->setValue($text);

        $headerString = $header->toString();

        $this->assertEquals('X-Test: start =?'.$this->charset.'?Q?'.
            'w=8Frd_w=8Frd?= then end =?'.$this->charset.'?Q?'.
            'w=8Frd?='."\r\n", $headerString,
            '%s: Adjacent encoded words should appear grouped with WSP encoded'
            );
    }

    public function testLanguageInformationAppearsInEncodedWords()
    {
        /* -- RFC 2231, 5.
        5.  Language specification in Encoded Words

        RFC 2047 provides support for non-US-ASCII character sets in RFC 822
        message header comments, phrases, and any unstructured text field.
        This is done by defining an encoded word construct which can appear
        in any of these places.  Given that these are fields intended for
        display, it is sometimes necessary to associate language information
        with encoded words as well as just the character set.  This
        specification extends the definition of an encoded word to allow the
        inclusion of such information.  This is simply done by suffixing the
        character set specification with an asterisk followed by the language
        tag.  For example:

                    From: =?US-ASCII*EN?Q?Keith_Moore?= <moore@cs.utk.edu>
        */

        $value = 'fo'.pack('C', 0x8F).'bar';

        $encoder = $this->getEncoder('Q');
        $encoder->shouldReceive('encodeString')
                ->once()
                ->with($value, \Mockery::any(), \Mockery::any(), \Mockery::any())
                ->andReturn('fo=8Fbar');

        $header = $this->getHeader('Subject', $encoder);
        $header->setLanguage('en');
        $header->setValue($value);
        $this->assertEquals("Subject: =?utf-8*en?Q?fo=8Fbar?=\r\n",
            $header->toString()
            );
    }

    public function testSetBodyModel()
    {
        $header = $this->getHeader('Subject', $this->getEncoder('Q', true));
        $header->setFieldBodyModel('test');
        $this->assertEquals('test', $header->getValue());
    }

    public function testGetBodyModel()
    {
        $header = $this->getHeader('Subject', $this->getEncoder('Q', true));
        $header->setValue('test');
        $this->assertEquals('test', $header->getFieldBodyModel());
    }

    private function getHeader($name, $encoder)
    {
        $header = new Swift_Mime_Headers_UnstructuredHeader($name, $encoder);
        $header->setCharset($this->charset);

        return $header;
    }

    private function getEncoder($type, $stub = false)
    {
        $encoder = $this->getMockery('Swift_Mime_HeaderEncoder')->shouldIgnoreMissing();
        $encoder->shouldReceive('getName')
                ->zeroOrMoreTimes()
                ->andReturn($type);

        return $encoder;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           INDX( 	 	ù¢             (   H  Ë       ’                    £+     x f     ¢+     ˆÇ¡’pk’ D¯∞f¯‘êzVø‹<’ˆÇ¡’pk’       „               D a t e H e a d e r T e s t . p h p   §+     ê z     ¢+     ©3“’pk’ D¯∞f¯‘êzVø‹<’©3“’pk’        3               I d e n t i f i c a t i o n H e a d e r T e s t . p h p       •+     Ä l     ¢+     qﬁ’pk’ D¯∞f¯‘ﬂ‹Xø‹<’qﬁ’pk’ 0      G/               M a i l b o x H e a d e r T e s t . p h p     ¶+     à x     ¢+     „‚’pk’ D¯∞f¯‘.?[ø‹<’„‚’pk  @      ú;               P a r a m e t e r i z e d H e a d e r T e s t . p h p ß+     x f     ¢+     ∫Í’pk’ D¯∞f¯‘ø†]ø‹<’∫Í’pk’       ê               P a t h H e a d e r T e s t . p h p   ®+     à v     ¢+     2Ò’pk’ D¯∞f¯‘ø†]ø‹<’2Ò’pk’ @      u3               U n s t r u c t u r e d H e a d e r T e s t . p h p                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          <?php

class Swift_Plugins_AntiFloodPluginTest extends \PHPUnit\Framework\TestCase
{
    public function testThresholdCanBeSetAndFetched()
    {
        $plugin = new Swift_Plugins_AntiFloodPlugin(10);
        $this->assertEquals(10, $plugin->getThreshold());
        $plugin->setThreshold(100);
        $this->assertEquals(100, $plugin->getThreshold());
    }

    public function testSleepTimeCanBeSetAndFetched()
    {
        $plugin = new Swift_Plugins_AntiFloodPlugin(10, 5);
        $this->assertEquals(5, $plugin->getSleepTime());
        $plugin->setSleepTime(1);
        $this->assertEquals(1, $plugin->getSleepTime());
    }

    public function testPluginStopsConnectionAfterThreshold()
    {
        $transport = $this->createTransport();
        $transport->expects($this->once())
                  ->method('start');
        $transport->expects($this->once())
                  ->method('stop');

        $evt = $this->createSendEvent($transport);

        $plugin = new Swift_Plugins_AntiFloodPlugin(10);
        for ($i = 0; $i < 12; ++$i) {
            $plugin->sendPerformed($evt);
        }
    }

    public function testPluginCanStopAndStartMultipleTimes()
    {
        $transport = $this->createTransport();
        $transport->expects($this->exactly(5))
                  ->method('start');
        $transport->expects($this->exactly(5))
                  ->method('stop');

        $evt = $this->createSendEvent($transport);

        $plugin = new Swift_Plugins_AntiFloodPlugin(2);
        for ($i = 0; $i < 11; ++$i) {
            $plugin->sendPerformed($evt);
        }
    }

    public function testPluginCanSleepDuringRestart()
    {
        $sleeper = $this->getMockBuilder('Swift_Plugins_Sleeper')->getMock();
        $sleeper->expects($this->once())
                ->method('sleep')
                ->with(10);

        $transport = $this->createTransport();
        $transport->expects($this->once())
                  ->method('start');
        $transport->expects($this->once())
                  ->method('stop');

        $evt = $this->createSendEvent($transport);

        $plugin = new Swift_Plugins_AntiFloodPlugin(99, 10, $sleeper);
        for ($i = 0; $i < 101; ++$i) {
            $plugin->sendPerformed($evt);
        }
    }

    private function createTransport()
    {
        return $this->getMockBuilder('Swift_Transport')->getMock();
    }

    private function createSendEvent($transport)
    {
        $evt = $this->getMockBuilder('Swift_Events_SendEvent')
                    ->disableOriginalConstructor()
                    ->getMock();
        $evt->expects($this->any())
            ->method('getSource')
            ->will($this->returnValue($transport));
        $evt->expects($this->any())
            ->method('getTransport')
            ->will($this->returnValue($transport));

        return $evt;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <?php

class Swift_Plugins_BandwidthMonitorPluginTest extends \PHPUnit\Framework\TestCase
{
    private $_monitor;

    private $_bytes = 0;

    protected function setUp()
    {
        $this->monitor = new Swift_Plugins_BandwidthMonitorPlugin();
    }

    public function testBytesOutIncreasesWhenCommandsSent()
    {
        $evt = $this->createCommandEvent("RCPT TO:<foo@bar.com>\r\n");

        $this->assertEquals(0, $this->monitor->getBytesOut());
        $this->monitor->commandSent($evt);
        $this->assertEquals(23, $this->monitor->getBytesOut());
        $this->monitor->commandSent($evt);
        $this->assertEquals(46, $this->monitor->getBytesOut());
    }

    public function testBytesInIncreasesWhenResponsesReceived()
    {
        $evt = $this->createResponseEvent("250 Ok\r\n");

        $this->assertEquals(0, $this->monitor->getBytesIn());
        $this->monitor->responseReceived($evt);
        $this->assertEquals(8, $this->monitor->getBytesIn());
        $this->monitor->responseReceived($evt);
        $this->assertEquals(16, $this->monitor->getBytesIn());
    }

    public function testCountersCanBeReset()
    {
        $evt = $this->createResponseEvent("250 Ok\r\n");

        $this->assertEquals(0, $this->monitor->getBytesIn());
        $this->monitor->responseReceived($evt);
        $this->assertEquals(8, $this->monitor->getBytesIn());
        $this->monitor->responseReceived($evt);
        $this->assertEquals(16, $this->monitor->getBytesIn());

        $evt = $this->createCommandEvent("RCPT TO:<foo@bar.com>\r\n");

        $this->assertEquals(0, $this->monitor->getBytesOut());
        $this->monitor->commandSent($evt);
        $this->assertEquals(23, $this->monitor->getBytesOut());
        $this->monitor->commandSent($evt);
        $this->assertEquals(46, $this->monitor->getBytesOut());

        $this->monitor->reset();

        $this->assertEquals(0, $this->monitor->getBytesOut());
        $this->assertEquals(0, $this->monitor->getBytesIn());
    }

    public function testBytesOutIncreasesAccordingToMessageLength()
    {
        $message = $this->createMessageWithByteCount(6);
        $evt = $this->createSendEvent($message);

        $this->assertEquals(0, $this->monitor->getBytesOut());
        $this->monitor->sendPerformed($evt);
        $this->assertEquals(6, $this->monitor->getBytesOut());
        $this->monitor->sendPerformed($evt);
        $this->assertEquals(12, $this->monitor->getBytesOut());
    }

    private function createSendEvent($message)
    {
        $evt = $this->getMockBuilder('Swift_Events_SendEvent')
                    ->disableOriginalConstructor()
                    ->getMock();
        $evt->expects($this->any())
            ->method('getMessage')
            ->will($this->returnValue($message));

        return $evt;
    }

    private function createCommandEvent($command)
    {
        $evt = $this->getMockBuilder('Swift_Events_CommandEvent')
                    ->disableOriginalConstructor()
                    ->getMock();
        $evt->expects($this->any())
            ->method('getCommand')
            ->will($this->returnValue($command));

        return $evt;
    }

    private function createResponseEvent($response)
    {
        $evt = $this->getMockBuilder('Swift_Events_ResponseEvent')
                    ->disableOriginalConstructor()
                    ->getMock();
        $evt->expects($this->any())
            ->method('getResponse')
            ->will($this->returnValue($response));

        return $evt;
    }

    private function createMessageWithByteCount($bytes)
    {
        $this->bytes = $bytes;
        $msg = $this->getMockBuilder('Swift_Mime_SimpleMessage')->disableOriginalConstructor()->getMock();
        $msg->expects($this->any())
            ->method('toByteStream')
            ->will($this->returnCallback([$this, 'write']));
        /*  $this->checking(Expectations::create()
              -> ignoring($msg)->toByteStream(any()) -> calls(array($this, 'write'))
          ); */

        return $msg;
    }

    public function write($is)
    {
        for ($i = 0; $i < $this->bytes; ++$i) {
            $is->write('x');
        }
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <?php

class Swift_Plugins_DecoratorPluginTest extends \SwiftMailerTestCase
{
    public function testMessageBodyReceivesReplacements()
    {
        $message = $this->createMessage(
            $this->createHeaders(),
            ['zip@button.tld' => 'Zipathon'],
            ['chris.corbyn@swiftmailer.org' => 'Chris'],
            'Subject',
            'Hello {name}, you are customer #{id}'
            );
        $message->shouldReceive('setBody')
                ->once()
                ->with('Hello Zip, you are customer #456');
        $message->shouldReceive('setBody')
                ->zeroOrMoreTimes();

        $plugin = $this->createPlugin(
            ['zip@button.tld' => ['{name}' => 'Zip', '{id}' => '456']]
            );

        $evt = $this->createSendEvent($message);

        $plugin->beforeSendPerformed($evt);
        $plugin->sendPerformed($evt);
    }

    public function testReplacementsCanBeAppliedToSameMessageMultipleTimes()
    {
        $message = $this->createMessage(
            $this->createHeaders(),
            ['zip@button.tld' => 'Zipathon', 'foo@bar.tld' => 'Foo'],
            ['chris.corbyn@swiftmailer.org' => 'Chris'],
            'Subject',
            'Hello {name}, you are customer #{id}'
            );
        $message->shouldReceive('setBody')
                ->once()
                ->with('Hello Zip, you are customer #456');
        $message->shouldReceive('setBody')
                ->once()
                ->with('Hello {name}, you are customer #{id}');
        $message->shouldReceive('setBody')
                ->once()
                ->with('Hello Foo, you are customer #123');
        $message->shouldReceive('setBody')
                ->zeroOrMoreTimes();

        $plugin = $this->createPlugin(
            [
                'foo@bar.tld' => ['{name}' => 'Foo', '{id}' => '123'],
                'zip@button.tld' => ['{name}' => 'Zip', '{id}' => '456'],
                ]
            );

        $evt = $this->createSendEvent($message);

        $plugin->beforeSendPerformed($evt);
        $plugin->sendPerformed($evt);
        $plugin->beforeSendPerformed($evt);
        $plugin->sendPerformed($evt);
    }

    public function testReplacementsCanBeMadeInHeaders()
    {
        $headers = $this->createHeaders([
            $returnPathHeader = $this->createHeader('Return-Path', 'foo-{id}@swiftmailer.org'),
            $toHeader = $this->createHeader('Subject', 'A message for {name}!'),
        ]);

        $message = $this->createMessage(
            $headers,
            ['zip@button.tld' => 'Zipathon'],
            ['chris.corbyn@swiftmailer.org' => 'Chris'],
            'A message for {name}!',
            'Hello {name}, you are customer #{id}'
            );

        $message->shouldReceive('setBody')
                ->once()
                ->with('Hello Zip, you are customer #456');
        $toHeader->shouldReceive('setFieldBodyModel')
                 ->once()
                 ->with('A message for Zip!');
        $returnPathHeader->shouldReceive('setFieldBodyModel')
                         ->once()
                         ->with('foo-456@swiftmailer.org');
        $message->shouldReceive('setBody')
                ->zeroOrMoreTimes();
        $toHeader->shouldReceive('setFieldBodyModel')
                 ->zeroOrMoreTimes();
        $returnPathHeader->shouldReceive('setFieldBodyModel')
                         ->zeroOrMoreTimes();

        $plugin = $this->createPlugin(
            ['zip@button.tld' => ['{name}' => 'Zip', '{id}' => '456']]
            );
        $evt = $this->createSendEvent($message);

        $plugin->beforeSendPerformed($evt);
        $plugin->sendPerformed($evt);
    }

    public function testReplacementsAreMadeOnSubparts()
    {
        $part1 = $this->createPart('text/plain', 'Your name is {name}?', '1@x');
        $part2 = $this->createPart('text/html', 'Your <em>name</em> is {name}?', '2@x');
        $message = $this->createMessage(
            $this->createHeaders(),
            ['zip@button.tld' => 'Zipathon'],
            ['chris.corbyn@swiftmailer.org' => 'Chris'],
            'A message for {name}!',
            'Subject'
            );
        $message->shouldReceive('getChildren')
                ->zeroOrMoreTimes()
                ->andReturn([$part1, $part2]);
        $part1->shouldReceive('setBody')
              ->once()
              ->with('Your name is Zip?');
        $part2->shouldReceive('