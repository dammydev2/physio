    }

    public function testConstruct()
    {
        try {
            $handler = ErrorHandler::register();
            $handler->throwAt(3, true);
            $this->assertEquals(3 | E_RECOVERABLE_ERROR | E_USER_ERROR, $handler->throwAt(0));
        } finally {
            restore_error_handler();
            restore_exception_handler();
        }
    }

    public function testDefaultLogger()
    {
        try {
            $handler = ErrorHandler::register();

            $logger = $this->getMockBuilder('Psr\Log\LoggerInterface')->getMock();

            $handler->setDefaultLogger($logger, E_NOTICE);
            $handler->setDefaultLogger($logger, [E_USER_NOTICE => LogLevel::CRITICAL]);

            $loggers = [
                E_DEPRECATED => [null, LogLevel::INFO],
                E_USER_DEPRECATED => [null, LogLevel::INFO],
                E_NOTICE => [$logger, LogLevel::WARNING],
                E_USER_NOTICE => [$logger, LogLevel::CRITICAL],
                E_STRICT => [null, LogLevel::WARNING],
                E_WARNING => [null, LogLevel::WARNING],
                E_USER_WARNING => [null, LogLevel::WARNING],
                E_COMPILE_WARNING => [null, LogLevel::WARNING],
                E_CORE_WARNING