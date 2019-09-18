e(E_USER_DEPRECATED, $exception->getSeverity());
            };

            $logger
                ->expects($this->once())
                ->method('log')
                ->will($this->returnCallback($warnArgCheck))
            ;

            $handler = ErrorHandler::register();
            $handler->setDefaultLogger($logger, E_USER_DEPRECATED);
            $this->assertTrue($handler->handleError(E_USER_DEPRECATED, 'foo', 'foo.php', 12, []));

            restore_error_handler();
            restore_exception_handler();

            $logger = $this->getMockBuilder('Psr\Log\LoggerInterface')->getMock();

            $line = null;
            $logArgCheck = function ($level, $message, $context) use (&$line) {
                $this->assertEquals('Notice: Undefined variable: undefVar', $message);
                $this->assertArrayHasKey('exception', $context);
                $exception = $context['exception'];
                $this->assertInstanceOf(SilencedErrorContext::class, $exception);
                $this->assertSame(E_NOTICE, $exception->getSeverity());
                $this->assertSame(__FILE__, $exception->getFile());
                $this->assertSame($line, $exception->getLine());
                $this->assertNotEmpty($exception->getTrace());
                $this->assertSame(1, $exception->count);
            };

            $logger
                ->expects($this->once())
                ->method('log')
                ->will($this->returnCallback($logArgCheck))
            ;

            $handler = ErrorHandler::register();
            $handler->setDefaultLogger($logger, E_NOTICE);
            $handler->screamAt(E_NOTICE);
            unset($undefVar);
            $line = __LINE__ + 1;
            @$undefVar++;

            restore_error_handler();
            restore_exception_handler();
        } catch (\Exception $e) {
            restore_error_handler();
            restore_exception_handler();

            throw $e;
        }
    }

    public function testHandleUserError()
    {
        try {
            $handler = ErrorHandler::register();
            $handler->throwAt(0, true);

            $e = null;
            $x = new \Exception('Foo');

            try {
                $f = new Fixtures\ToStringThrower($x);
                $f .= ''; // Trigger $f->__toString()
            } catch (\Exception $e) {
            }

            $this->assertSame($x, $e);
        } finally {
            restore_error_handler();
            restore_exception_handler();
        }
    }

    public function testHandleDeprecation()
    {
        $logArgCheck = function ($level, $message, $context) {
            $this->assertEquals(LogLevel::INFO, $level);
            $this->assertArrayHasKey('exception', $context);
            $exception = $context['exception'];
            $this->assertInstanceOf(\ErrorException::class, $exception);
            $this->assertSame('User Deprecated: Foo deprecation', $exception->getMessage());
        };

        $logger = $this->getMockBuilder('Psr\Log\LoggerInterface')->getMock();
        $logger
            ->expects($this->once())
            ->method('log')
            ->will($this->returnCallback($logArgCheck))
        ;

        $handler = new ErrorHandler();
        $handler->setDefaultLogger($logger);
        @$handler->handleError(E_USER_DEPRECATED, 'Foo deprecation', __FILE__, __LINE__, []);

        restore_error_handler();
    }

    public function testHandleException()
    {
        try {
            $handler = ErrorHandler::register();

            $exception = new \Exception('foo');

            $logger = $this->getMockBuilder('Psr\Log\LoggerInterface')->getMock();

            $logArgCheck = function ($level, $message, $context) {
                $this->assertSame('Uncaught Exception: f