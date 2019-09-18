ost:', $data['icon_emoji']);
        $this->assertArrayHasKey('icon_url', $data2);
        $this->assertSame('http://github.com/Seldaek/monolog', $data2['icon_url']);
    }

    public function testAttachmentsNotPresentIfNoAttachment()
    {
        $record = new SlackRecord(null, null, false);
        $data = $record->getSlackData($this->getRecord());

        $this->assertArrayNotHasKey('attachments', $data);
    }

    public function testAddsOneAttachment()
    {
        $record = new SlackRecord();
        $data = $record->getSlackData($this->getRecord());

        $this->assertArrayHasKey('attachments', $data);
        $this->assertArrayHasKey(0, $data['attachments']);
        $this->assertInternalType('array', $data['attachments'][0]);
    }

    public function testTextEqualsMessageIfNoAttachment()
    {
        $message = 'Test message';
        $record = new SlackRecord(null, null, false);
        $data = $record->getSlackData($this->getRecord(Logger::WARNING, $message));

        $this->assertArrayHasKey('text', $data);
        $this->assertSame($message, $data['text']);
    }

    public function testTextEqualsFormatterOutput()
    {
        $formatter = $this->getMock('Monolog\\Formatter\\FormatterInterface');
        $formatter
            ->expects($this->any())
            ->method('format')
            ->will($this->returnCallback(function ($record) { return $record['message'] . 'test'; }));

        $formatter2 = $this->getMock('Monolog\\Formatter\\FormatterInterface');
        $formatter2
            ->expects($this->any())
            ->method('format')
            ->will($this->returnCallback(function ($record) { return $record['message'] . 'test1'; }));

        $message = 'Test message';
        $record = new SlackRecord(null, null, false, null, false, false, array(), $formatter);
        $data = $record->getSlackData($this->getRecord(Logger::WARNING, $message));

        $this->assertArrayHasKey('text', $data);
        $this->assertSame($message . 'test', $data['text']);

        $record->setFormatter($formatter2);
        $data = $record->getSlackData($this->getRecord(Logger::WARNING, 