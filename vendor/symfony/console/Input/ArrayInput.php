tion code is 0');
    }

    public function testRunDispatchesExitCodeOneForExceptionCodeZero()
    {
        $passedRightValue = false;

        // We can assume here that some other test asserts that the event is dispatched at all
        $dispatcher = new EventDispatcher();
        $dispatcher->addListener('console.terminate', function (ConsoleTerminateEvent $event) use (&$passedRightValue) {
            $passedRightValue = (1 === $event->getExitCode());
        });

        $application = new Application();
        $application->setDispatcher($dispatcher);
        $application->setAutoExit(false);

        $application->register('test')->setCode(function (InputInterface $input, OutputInterface $output) {
            throw new \Exception();
        });

        $tester = new ApplicationTester($application);
        $tester->run(['command' => 'test']);

        $this->assertTrue($passedRightValue, '-> exit code 1 was passed in the console.terminate event');
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage An option with shortcut "e" already exists.
     */
    public function testAddingOptionWithDuplicateShortcut()
    {
        $dispatcher = new EventDispatcher();
        $application = new Application();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);
        $application->setDispatcher($dispatcher);

        $application->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'Environment'));

        $application
            ->register('foo')
            ->setAliases(['f'])
            ->setDefinition([new InputOption('survey', 'e', InputOption::VALUE_REQUIRED, 'My option with a shortcut.')])
            ->setCode(function (InputInterface $input, OutputInterface $output) {})
        ;

        $input = new ArrayInput(['command' => 'foo']);
        $output = new NullOutput();

        $application->run($input, $output);
    }

    /**
     * @expectedException \LogicException
     * @dataProvider getAddingAlreadySetDefinitionElementData
     */
    public function testAddingAlreadySetDefinitionElementData($def)
    {
        $application = new Application();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);
        $application
            ->register('foo')
            ->setDefinition([$def])
            ->setCode(function (InputInterface $input, OutputInterface $output) {})
        ;

        $input = new ArrayInput(['command' => 'foo']);
        $output = new NullOutput();
        $application->run($input, $output);
    }

    public function getAddingAlreadySetDefinitionElementData()
    {
        return [
            [new InputArgument('command', InputArgument::REQUIRED)],
            [new InputOption('quiet', '', InputOption::VALUE_NONE)],
            [new InputOption('query', 'q', InputOption::VALUE_NONE)],
        ];
    }

    public function testGetDefaultHelperSetReturnsDefaultValues()
    {
        $application = new Application();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $helperSet = $application->getHelperSet();

        $this->assertTrue($helperSet->has('formatter'));
    }

    public function testAddingSingleHelperSetOverwritesDefaultValues()
    {
        $application = new Application();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $application->setHelperSet(new HelperSet([new FormatterHelper()]));

        $helperSet = $application->getHelperSet();

        $this->assertTrue($helperSet->has('formatter'));

        // no other default helper set should be returned
        $this->assertFalse($helperSet->has('dialog'));
        $this->assertFalse($helperSet->has('progress'));
    }

    public function testOverwritingDefaultHelperSetOverwritesDefaultValues()
    {
        $application = new CustomApplication();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $application->setHelperSet(new HelperSet([new FormatterHelper()]));

        $helperSet = $application->getHelperSet();

        $this->assertTrue($helperSet->has('formatter'));

        // no other default helper set should be returned
        $this->assertFalse($helperSet->has('dialog'));
        $this->assertFalse($helperSet->has('progress'));
    }

    public function testGetDefaultInputDefinitionReturnsDefaultValues()
    {
        $application = new Application();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $inputDefinition = $application->getDefinition();

        $this->assertTrue($inputDefinition->hasArgument('command'));

        $this->assertTrue($inputDefinition->hasOption('help'));
        $this->assertTrue($inputDefinition->hasOption('quiet'));
        $this->assertTrue($inputDefinition->hasOption('verbose'));
        $this->assertTrue($inputDefinition->hasOption('version'));
        $this->assertTrue($inputDefinition->hasOption('ansi'));
        $this->assertTrue($inputDefinition->hasOption('no-ansi'));
        $this->assertTrue($inputDefinition->hasOption('no-interaction'));
    }

    public function testOverwritingDefaultInputDefinitionOverwritesDefaultValues()
    {
        $application = new CustomApplication();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $inputDefinition = $application->getDefinition();

        // check whether the default arguments and options are not re