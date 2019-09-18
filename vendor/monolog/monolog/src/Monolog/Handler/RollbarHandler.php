 }

    /**
     * @covers Monolog\Formatter\GelfMessageFormatter::format
     */
    public function testFormatWithContextContainingException()
    {
        $formatter = new GelfMessageFormatter();
        $record = array(
            'level' => Logger::ERROR,
            'level_name' => 'ERROR',
            'channel' => 'meh',
            'context' => array('from' => 'logger', 'exception' => array(
                'class' => '\Exception',
                'file'  => '/some/file/in/dir.php:56',
                'trace' => array('/some/file/1.php:23', '/some/file/2.php:3'),
            )),
            'datetime' => new \DateTime("@0"),
            'extra' => array(),
            'message' => 'log',
        );

        $message = $formatter->format($record);

        $this->assertInstanceOf('Gelf\Message', $message);

        $this->assertEquals("/some/file/in/dir.php", $message->getFile());
        $this->assertEquals("56", $message->getLine());
    }

    /**
     * @covers Monolog\Formatter\GelfMessageFormatter::format
     */
    public function testFormatWithExtra()
    {
        $formatter = new GelfMessageFormatter();
        $record = array(
            'level' => Logger::ERROR,
            'level_name' => 'ERROR',
            'channel' => 'meh',
            'context' => array('from' => 'logger'),
            'datetime' => new \DateTime("@0"),
            'extra' => array('key' => 'pair'),
            'message' => 'log',
        );

        $message = $formatter->format($record);

        $this->assertInstanceOf('Gelf\Message', $message);

        $message_array = $message->toArray();

        $this->assertArrayHasKey('_key', $message_array);
        $this->assertEquals('pair', $message_array['_key']);

        // Test with extraPrefix
        $formatter = new GelfMessageFormatter(null, 'EXT');
        $message = $formatter->format($record);

        $this->assertInstanceOf('Gelf\Message', $message);

        $message_array = $message->toArray();

        $this->assertArrayHasKey('_EXTkey', $message_array);
        $this->assertEquals('pair', $message_array['_EXTkey']);
    }

    public function testFormatWithLargeData()
    {
        $formatter = new GelfMessageFormatter();
        $record = array(
            'level' => Logger::ERROR,
            'level_name' => 'ERROR',
            'channel' => 'meh',
            'context' => array('exception' => str_repeat(' ', 32767)),
            'datetime' => new \DateTime("@0"),
            'extra' => array('key' => str_repeat(' ', 32767)),
            'message' => 'log'
        );
        $message = $formatter->format($record);
        $messageArray = $message->toArray();

        // 200 for padding + metadata
        $length = 200;

        foreach ($messageArray as $key => $value) {
            if (!in_array($key, array('level', 'timestamp'))) {
                $length += strlen($value);
            }
        }

        $this->assertLessThanOrEqual(65792, $length, 'The message length is no longer than the maximum allowed length');
    }

    public function testFormatWithUnlimitedLength()
    {
        $formatter = new GelfMessageFormatter('LONG_SYSTEM_NAME', null, 'ctxt_', PHP_INT_MAX);
        $record = array(
            'level' => Logger::ERROR,
            'level_name' => 'ERROR',
            'channel' => 'meh',
            'context' => array('exception' => str_repeat(' ', 32767 * 2)),
            'datetime' => new \DateTime("@0"),
            'extra' => array('key' => str_repeat(' ', 32767 * 2)),
            'message' => 'log'
        );
        $message = $formatter->format($record);
        $messageArray = $message->toArray();

        // 200 for padding + metadata
        $length = 200;

        foreach ($messageArray as $key => $value) {
            if (!in_array($key, array('level', 'timestamp'))) {
                $length += strlen($value);
            }
        }

        $this->assertGreaterThanOrEqual(13