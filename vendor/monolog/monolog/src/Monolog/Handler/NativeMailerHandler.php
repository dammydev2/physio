useMicrosecondTimestampsProvider()
    {
        return array(
            // this has a very small chance of a false negative (1/10^6)
            'with microseconds' => array(true, 'assertNotSame'),
            'without microseconds' => array(false, PHP_VERSION_ID >= 70100 ? 'assertNotSame' : 'assertSame'),
        );
    }

    /**
     * @covers Monolog\Logger::setExceptionHandler
     */
    public function testSetExceptionHandler()
    {
        $logger = new Logger(__METHOD__);
        $this->assertNull($logger->getExceptionHandler());
        $callback = function ($ex) {
        };
        $logger->setExceptionHandler($callback);
        $this->assertEquals($callback, $logger->getExceptionHandler());
    }

    /**
     * @covers Monolog\Logger::setExceptionHandler
     * @expectedException InvalidArgumentException
     */
    public function testBadExceptionHandlerType()
    {
        $logger = new Logger(__METHOD__);
        $logger->setExceptionHandler(false);
    }

    /**
     * @covers Monolog\Logger::handleException
     * @expectedException Exception
     */
    public function testDefaultHandleException()
    {
        $logger = new Logger(__METHOD__);
        $handler = $this->getMock('Monolog\Handler\HandlerInterface');
        $handler->expects($this->any())
            ->method('isHandling')
            ->will($this->returnValue(true))
        ;
        $handler->expects($this->any())
            ->method('handle')
            ->will($this->throwException(new \Exception('Some handler exception')))
        ;
        $logger->pushHandler($handler);
        $logger->info('test');
    }

    /**
     * @covers Monolog\Logger::handleException
     * @covers Monolog\Logger::addRecord
     */
    public function testCustomHandleException()
    {
        $logger = new Logger(__METHOD__);
        $that = $this;
        $logger->setExceptionHandler(function ($e, $record) use ($that) {
            $that->assertEquals($e->getMessage(), 'Some handler exception');
            $that->assertTrue(is_array($record));
            $that->assertEquals($record['message'], 'test');
        });
        $handler = $this->getMock('Monolog\Handler\HandlerInterface');
        $handler->expects($this->any())
            ->method('isHandling')
            ->will($this->returnValue(true))
        ;
        $handler->expects($this->any())
            ->method('handle')
            ->will($this->throwException(new \Exception('Some handler exception')))
        ;
        $logger->pushHandler($handler);
        $logger->info('test');
    }

    public function testReset()
    {
        $logger = new Logger('app');

        $testHandler = new Handler\TestHandler();
        $bufferHandler = new Handler\BufferHandler($testHandler);
        $groupHandler = new Handler\GroupHandler(array($bufferHandler));
        $fingersCrossedHandler = new Handler\FingersCrossedHandler($groupHandler);

        $logger->pushHandler($fingersCrossedHandler);

        $processorUid1 = new Processor\UidProcessor(10);
        $uid1 = $processorUid1->getUid();
        $groupHandler->pushProcessor($processorUid1);

        $processorUid2 = new Processor\UidProcessor(5);
        $uid2 = $processorUid2->getUid();
        $logger->pushProcessor($processorUid2);

        $getProperty = function ($object, $property) {
            $reflectionProperty = new \ReflectionProperty(get_class($object), $property);
            $reflectionProperty->setAccessible(true);

            return $reflectionProperty->getValue($object);
        };
        $that = $this;
        $assertBufferOfBufferHandlerEmpty = function () use ($getProperty, $bufferHandler, $that) {
            $that->assertEmpty($getProperty($bufferHandler, 'buffer'));
        };
        $assertBuffersEmpty = function() use ($assertBufferOfBufferHandlerEmpty, $getProperty, $fingersCrossedHandler, $that) {
            $assertBufferOfBufferHandlerEmpty();
            $that->assertEmpty($getProperty($fingersCrossedHandler, 'buffer'));
        };

        $logger->debug('debug');
        $logger->reset();
        $assertBuffersEmpty();
        $this->assertFalse($testHandler->hasDebugRecords());
        $this->assertFalse($testHandler->hasErrorRecords());
        $this->assertNotSame($uid1, $uid1 = $processorUid1->getUid());
        $this->assertNotSame($uid2, $uid2 = $processorUid2->getUid());

        $logger->debug('debug');
        $logger->error('error');
        $logger->reset();
        $assertBuffersEmpty();
        $this->assertTrue($testHandler->hasDebugRecords());
        $this->assertTrue($testHandler->hasErrorRecords());
        $this->assertNotSame($uid1, $uid1 = $processorUid1->getUid());
        $this->assertNotSame($uid2, $uid2 = $processorUid2->getUid());

        $logger->info('info');
        $this->assertNotEmpty($getProperty($fingersCrossedHandler, 'buffer'));
        $assertBufferOfBufferHandlerEmpty();
        $this->assertFalse($testHandler->hasInfoRecords());

        $logger->reset();
        $assertBuffersEmpty();
        $this->assertFalse($testHandler->hasInfoRecords());
        $this->assertNotSame($uid1, $uid1 = $processorUid1->getUid());
        $this->assertNotSame($uid2, $