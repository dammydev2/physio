
                $this->assertEquals('Fatal Parse Error: foo', $message);
                $this->assertArrayHasKey('exception', $context);
                $this->assertInstanceOf(\Exception::class, $context['exception']);
            };

            $logger
                ->expects($this->once())
                ->method('log')
                ->will($this->returnCallback($logArgCheck))
            ;

            $handler->setDefaultLogger($logger, E_PARSE);

            $handler->handleFatalError($error);

            restore_error_handler();
            restore_exception_handler();
        } catch (\Exception $e) {
            restore_error_handler();
            restore_exception_handler();

            throw $e;
        }
    }

    public function testHandleErrorException()
    {
        $exception = new \Error("Class 'IReallyReallyDoNotExistAnywhereInTheRepositoryISwear' not found");

        $handler = new ErrorHandler();
        $handler->setExceptionHandler(function () use (&$args) {
            $args = \func_get_args();
        });

        $handler->handleException($exception);

        $this->assertInstanceOf('Symfony\Component\Debug\Exception\ClassNotFoundException', $args[0]);
        $this->assertStringStartsWith("Attempted to load class \"IReallyReallyDoNotExistAnywhereInTheRepositoryISwear\" from the global namespace.\nDid you forget a \"use\" statement", $args[0]->getMessage());
    }

    /**
     * @expectedException \Exception
     */
    public function testCustomExceptionHandler