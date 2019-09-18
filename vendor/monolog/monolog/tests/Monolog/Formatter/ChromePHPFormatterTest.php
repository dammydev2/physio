d&room_id=room1&from=Monolog$/', $content);
    }

    /**
     * @depends testWriteV2
     */
    public function testWriteContentV2($content)
    {
        $this->assertRegexp('/notify=false&message=test1&message_format=text&color=red&from=Monolog$/', $content);
    }

    /**
     * @depends testWriteV2Notify
     */
    public function testWriteContentV2Notify($content)
    {
        $this->assertRegexp('/notify=true&message=test1&message_format=text&color=red&from=Monolog$/', $content);
    }

    public function testWriteContentV2WithoutName()
    {
        $this->createHandler('myToken', 'room1', null, false, 'hipchat.foo.bar', 'v2');
        $this->handler->handle($this->getRecord(Logger::CRITICAL, 'test1'));
        fseek($this->res, 0);
        $content = fread($this->res, 1024);

        $this->assertRegexp('/notify=false&message=test1&message_format=text&color=red$/', $content);

        return $content;
    }

    public function testWriteWithComplexMessage()
    {
        $this->createHandler();
        $this->handler->handle($this->getRecord(Logger::CRITICAL, 'Backup of database "example" finished in 16 minutes.'));
        fseek($this->res, 0);
        $content = fread($this->res, 1024);

        $this->assertRegexp('/message=Backup\+of\+database\+%22example%22\+finished\+in\+16\+minutes\./', $content);
    }

    public function testWriteTruncatesLongMessage()
    {
        $this->createHandler();
        $this->handler->handle($this->getRecord(Logger::CRITICAL, str_repeat('abcde', 2000)));
        fseek($this->res, 0);
        $content = fread($this->res, 12000);

        $this->assertRegexp('/message='.str_repeat('abcde', 1900).'\+%5Btruncated%5D/', $content);
    }

    /**
     * @dataProvider provideLevelColors
     */
    public function testWriteWithErrorLevelsAndColors($level, $expectedColor)
    {
        $this->createHandler();
        $this->handler->handle($this->getRecord($level, 'Backup of database "example" finished in 16 minutes.'));
        fseek($this->res, 0);
        $content = fread($this->res, 1024);

        $this->assertRegexp('/color='.$expectedColor.'/', $content);
    }

    public function provideLevelColors()
    {
        return array(
            array(Logger::DEBUG,    'gray'),
            array(Logger::INFO,     'green'),
            array(Logger::WARNING,  'yellow'),
            array(Logger::ERROR,    'red'),
            array(Logger::CRITICAL, 'red'),
            array(Logger::ALERT,    'red'),
            array(Logger::EMERGENCY,'red'),
            array(Logger::NOTICE,   'green'),
        );
    }

    /**
     * @dataProvider provideBatchRecords
     */
    public function testHandleBatch($records, $expectedColor)
    {
        $this->createHandler();

        $this->handler->handleBatch($records);

        fseek($this->res, 0);
        $content = fread($this->res, 1024);

        $this->assertRegexp('/color='.$expectedColor.'/', $content);
    }

    public function provideBatchRecords()
    {
        return array(
            array(
                array(
                    array('level' => Logger::WARNING, 'message' => 'Oh bugger!', 'level_name' => 'warning', 'datetime' => new \DateTime()),
                    array('level' => Logger::NOTICE, 'message' => 'Something noticeable happened.', 'level_name' => 'notice', 'datetime' => new \DateTime()),
                    array('level' => Logger::CRITICAL, 'message' => 'Everything is broken!', 'level_name' => 'critical', 'datetime' => new \DateTime()),
                ),
                'red',
            ),
            array(
                array(
                    array('level' => Logger::WARNING, 'message' => 'Oh bugger!', 'level_name' => 'warning', 'datetime' => new \DateTime()),
                    array('level' => Logger::NOTICE, 'message' => 'Something noticeable happened.', 'level_name' => 'notice', 'datetime' => new \DateTime()),
                ),
                'yellow',
            ),
            array(
                array(
                    array('level' => Logger::DEBUG, 'message' => 'Just debugging.', 'level_name' => 'debug', 'datetime' => new \DateTime()),
                    array('level' => Logger::NOTICE, 'message' => 'Something noticeable happe