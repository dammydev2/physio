    ->will($this->returnCallback($logArgCheck))
            ;

            $handler->setDefaultLogger($logger, E_ERROR);

            try {
                $handler->handleException($exception);
                $this->fail('Exception expected');
            } catch (\Exception $e) {
                $this->assertSame($exception, $e);
            }

            $handler->setExceptionHandler(function ($e) use ($exception) {
                $this->assertSame($exception, $e);
            });

            $handler->handleException($exception);
        } finally {
            restore_error_handler();
            restore_exception_handler();
        }
    }

    public function testBootstrappingLogger()
    {
        $bootLogger = new BufferingLogger();
        $handler = new ErrorHandler($bootLogger);

        $loggers = [
            E_DEPRECATED => [$bootLogger, LogLevel::INFO],
            E_USER_DEPRECATED => [$bootLogger, LogLevel::INFO],
            E_NOTICE => [$bootLogger, LogLevel::WARNING],
            E_USER_NOTICE => [$bootLogger, LogLevel::WARNING],
            E_STRICT => [$bootLogger, LogLevel::WARNING],
            E_WARNING => [$bootLogger, LogLevel::WARNING],
            E_USER_WARNING => [$bootLogger, LogLevel::WARNING],
            E_COMPILE_WARNING => [$bootLogger, LogLevel::WARNING],
            E_CORE_WARNING => [$bootLogger, LogLevel::WARNING],
            E_USER_ERROR => [$bootLogger, LogLevel::CRITICAL],
            E_RECOVERABLE_ERROR => [$bootLogger, LogLevel::CRITICAL],
            E_COMPILE_ERROR => [$bootLogger, LogLevel::CRITICAL],
            E_PARSE => [$bootLogger, LogLevel::CRITICAL],
            E_ERROR => [$bootLogger, LogLevel::CRITICAL],
            E_CORE_ERROR => [$bootLogger, LogLevel::CRITICAL],
        ];

        $this->assertSame($loggers, $handler->setLoggers([]));

        $handler->handleError(E_DEPRECATED, 'Foo message', __FILE__, 123, []);

        $logs = $bootLogger->cleanLogs();

        $this->assertCount(1, $logs);
        $log = $logs[0];
        $this->assertSame('info', $log[0