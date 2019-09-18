er);
        $application->setAutoExit(false);

        $application->register('foo')->setCode(function (InputInterface $input, OutputInterface $output) {
            $output->write('foo.');
        });

        $tester = new ApplicationTester($application);
        $tester->run(['command' => 'foo', '--no-interaction' => true]);

        $this->assertTrue($noInteractionValue);
        $this->assertFalse($quietValue);
    }

    public function testRunWithDispatcherAddingInputOptions()
    {
        $extraValue = null;

        $dispatcher = $this->getDispatcher();
        $dispatcher->addListener('console.command', function (ConsoleCommandEvent $event) use (&$extraValue) {
            $definition = $event->getCommand()->getDefinition();
            $input = $event->getInput();

            $definition->addOption(new InputOption('extra', null, InputOption::VALUE_REQUIRED));
            $input->bind($definition);

            $extraValue = $input->getOption('extra');
        });

        $application = new Application();
        $application->setDispatcher($dispatcher);
        $application->setAutoExit(false);

        $application->register('foo')->setCode(function (InputInterface $input, OutputInterface $output) {
            $output->write('foo.');
        });

        $tester = new ApplicationTester($application);
        $tester->run(['command' => 'foo', '--extra' => 'some test value']);

        $this->assertEquals('some test value', $extraValue);
    }

    public function testSetRunCustomDefaultCommand()
    {
        $command = new \FooCommand();

        $application = new Application();
        $application->setAutoExit(false);
        $application->add($command);
        $application->setDefaultCommand($command->getName());

        $tester = new ApplicationTester($application);
        $tester->run([], ['interactive' => false]);
        $this->assertEquals('called'.PHP_EOL, $tester->getDisplay(), 'Application runs the default set command if different from \'list\' command');

        $application = new CustomDefaultCommandApplication();
        $application->setAutoExit(false);

        $tester = new ApplicationTester($application);
        $tester->run([], ['interactive' => false]);

        $this->assertEquals('called'.PHP_EOL, $tester->getDisplay(), 'Application runs the default set command if different from \'list\' command');
    }

    public function testSetRunCustomDefaultCommandWithOption()
    {
        $command = new \FooOptCommand();

        $application = new Application();
        $application->setAutoExit(false);
        $application->add($command);
        $application->setDefaultCommand($command->getName());

        $tester = new ApplicationTester($application);
        $tester->run(['--fooopt' => 'opt'], ['interactive' => false]);

        $this->assertEquals('called'.PHP_EOL.'opt'.PHP_EOL, $tester->getDisplay(), 'Application runs the default set command if different from \'list\' command');
    }

    public function testSetRunCustomSingleCommand()
    {
        $command = new \FooCommand();

        $application = new Application();
        $application->setAutoExit(false);
        $application->add($command);
        $application->setDefaultCommand($command->getName(), true);

        $tester = new ApplicationTester($application);

        $tester->run([]);
        $this->assertContains('called', $tester->getD