 ->method('handle')
            ->will($this->returnValue(true))
        ;
        $logger->pushHandler($handler2);

        $logger->debug('test');
    }

    /**
     * @covers Monolog\Logger::isHandling
     */
    public function testIsHandling()
    {
        $logger = new Logger(__METHOD__);

        $handler1 = $this->getMock('Monolog\Handler\HandlerInterface');
        $handler1->expects($this->any())
            ->method('isHandling')
            ->will($this->returnValue(false))
        ;

        $logger->pushHandler($handler1);
        $this->assertFalse($logger->isHandling(Logger::DEBUG));

        $handler2 = $this->getMock('Monolog\Handler\HandlerInterface');
        $handler2->expects($this->any())
            ->method('isHandling')
            ->will($this->returnValue(true))
        ;

        $logger->pushHandler($handler2);
        $this->assertTrue($logger->isHandling(Logger::DEBUG));
    }

    /**
     * @dataProvider logMethodProvider
     * @covers Monolog\Logger::addDebug
     * @covers Monolog\Logger::addInfo
     * @covers Monolog\Logger::addNotice
     * @covers Monolog\Logger::addWarning
     * @covers Monolog\Logger::addError
     * @covers Monolog\Logger::addCritical
     * @covers Monolog\Logger::addAlert
     * @covers Monolog\Logger::addEmergency
     * @covers Monolog\Logger::debug
     * @covers Monolog\Logger::info
     * @covers Monolog\Logger::notice
     * @covers Monolog\Logger::warn
     * @covers Monolog\Logger::err
     * @covers Monolog\Logger::crit
     * @covers Monolog\Logger::alert
     * @covers Monolog\Logger::emerg
     */
    