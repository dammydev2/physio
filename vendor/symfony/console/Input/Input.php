application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $application->register('foo')->setCode(function (InputInterface $input, OutputInterface $output) {
            throw new \RuntimeException('foo');
        });

        $tester = new ApplicationTester($application);
        $tester->run(['command' => 'foo']);
    }

    public function testRunDispatchesAllEventsWithException()
    {
        $application = new Application();
        $application->setDispatcher($this->getDispatcher());
        $application->setAutoExit(false);

        $application->register('foo')->setCode(function (InputInterface $input, OutputInterface $output) {
            $output->write('foo.');

            throw new \RuntimeException('foo');
        });

        $tester = new ApplicationTester($application);
        $tester->run(['command' => 'foo']);
        $this->assertContains('before.foo.error.after.', $tester->getDisplay());
    }

    public function testRunDispatchesAllEventsWithExceptionInListener()
    {
        $dispatcher = $this->getDispatcher();
        $dispatcher->addListener('console.command', function () {
            throw new \RuntimeException('foo');
        });

        $application = new Application();
        $application->setDispatcher($dispatcher);
        $application->setAutoExit(false);

        $application->register('foo')->setCode(function (InputInterface $input, OutputInterface $output) {
            $output->write('foo.');
        });

        $tester = new ApplicationTester($application);
        $tester->run(['command' => 'foo']);
        $this->assertContains('before.error.after.', $tester->getDisplay());
    }

    public function testRunWithError()
    {
        $application = new Application();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $application->register('dym')->setCode(function (InputInterface $input, OutputInterface $output) {
            $output->write('dym.');

            throw new \Error('dymerr');
        });

        $tester = new ApplicationTester($application);

        try {
            $tester->run(['command' => 'dym']);
            $this->fail('Error expected.');
        } catch (\Error $e) {
            $this->assertSame('dymerr', $e->getMessage());
        }
    }

    public function testRunAllowsErrorListenersToSilenceTheException()
    {
        $dispatcher = $this->getDispatcher();
        $dispatcher->addListener('console.error', function (ConsoleErrorEvent $event) {
            $event->getOutput()->write('silenced.');

            $event->setExitCode(0);
        });

        $dispatcher->addListener('console.command', function () {
            throw new \RuntimeException('foo');
        });

        $application = new Application();
        $application->setDispatcher($dispatcher);
        $application->setAutoExit(false);

        $application->register('foo')->setCode(function (InputInterface $input, OutputInterface $output) {
            $output->write('foo.');
        });

        $tester = new ApplicationTester($application);
        $tester->run(['command' => 'foo']);
        $this->assertContains('before.error.silenced.after.', $tester->getDisplay());
        $this->assertEquals(ConsoleCommandEvent::RETURN_CODE_DISABLED, $tester->getStatusCode());
    }

    public function testConsoleErrorEventIsTriggeredOnCommandNotFound()
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addListener('console.error', function (ConsoleErrorEvent $event) {
            $this->assertNull($event->getCommand());
            $this->assertInstanceOf(CommandNotFoundException::class, $event->getError());
            $event->getOutput()->write('silenced command not found');
        });

        $application = new Application();
        $application->setDispatcher($dispatcher);
        $application->setAutoExit(false);

        $tester = new ApplicationTester($application);
        $tester->run(['command' => 'unknown']);
        $this->assertContains('silenced command not found', $tester->getDisplay());
        $this->assertEquals(1, $tester->getStatusCode());
    }

    public function testErrorIsRethrownIfNotHandledByConsoleErrorEvent()
    {
        $application = new Application();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);
        $application->setDispatcher(new EventDispatcher());

        $application->register('dym')->setCode(function (InputInterface $input, OutputInterface $output) {
            new \UnknownClass();
        });

        $tester = new ApplicationTester($application);

        try {
            $tester->run(['command' => 'dym']);
            $this->fail('->run() should rethrow PHP errors if not handled via ConsoleErrorEvent.');
        } catch (\Error $e) {
            $this->assertSame($e->getMessage(), 'Class \'UnknownClass\' not found');
        }
    }

    /**
     * @expected