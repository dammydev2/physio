   $logger = new Logger(__METHOD__);
        $handler1 = new TestHandler;
        $handler2 = new TestHandler;

        $logger->pushHandler($handler1);
        $logger->pushHandler($handler2);

        $this->assertEquals($handler2, $logger->popHandler());
        $this->assertEquals($handler1, $logger->popHandler());
        $logger->popHandler();
    }

    /**
     * @covers Monolog\Logger::setHandlers
     */
    public function testSetHandlers()
    {
        $logger = new Logger(__METHOD__);
        $handler1 = new TestHandler;
        $handler2 = new TestHandler;

        $logger->pushHandler($handler1);
        $logger->setHandlers(array($handler2));

        // handler1 has been removed
        $this->assertEquals(array($handler2), $logger->getHandlers());

        $logger->setHandlers(array(
            "AMapKey" => $handler1,
            "Woop" => $handler2,
        ));

        // Keys have been scrubbed
        $this->assertEquals(array($handler1, $handler2), $logger->getHandlers());
    }

    /**
     * @covers Monolog\Logger::pushProcessor
     * @covers Monolog\Logger::popProcessor
     * @expectedException LogicException
     */
    public function testPushPopProcessor()
    {
        $logger = new Logger(__METHOD__);
        $processor1 = new WebProcessor;
        $processor2 = new WebProcessor;

        $logger->pushProcessor($processor1);
        $logger->pushProcessor($processor2);

        $this->assertEquals($processor2, $logger->popProcessor());
        $this->assertEquals($processor1, $logger->popProcessor());
        $logger->popProcessor();
    }

    /**
     * @cov