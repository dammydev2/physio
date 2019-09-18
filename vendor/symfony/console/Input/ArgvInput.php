ceptionLineBreaks()
    {
        $application = $this->getMockBuilder('Symfony\Component\Console\Application')->setMethods(['getTerminalWidth'])->getMock();
        $application->setAutoExit(false);
        $application->expects($this->any())
            ->method('getTerminalWidth')
            ->will($this->returnValue(120));
        $application->register('foo')->setCode(function () {
            throw new \InvalidArgumentException("\n\nline 1 with extra spaces        \nline 2\n\nline 4\n");
        });
        $tester = new ApplicationTester($application);

        $tester->run(['command' => 'foo'], ['decorated' => false]);
        $this->assertStringMatchesFormatFile(self::$fixturesPath.'/application_renderexception_linebreaks.txt', $tester->getDisplay(true), '->renderException() keep multiple line breaks');
    }

    public function testRenderAnonymousException()
    {
        $application = new Application();
        $application->setAutoExit(false);
        $application->register('foo')->setCode(function () {
            throw new class('') extends \InvalidArgumentException {
            };
        });
        $tester = new ApplicationTester($application);

        $tester->run(['command' => 'foo'], ['decorated' => false]);
        $this->assertContains('[InvalidArgumentException@anonymous]', $tester->getDisplay(true));

        $application = new Application();
        $application->setAutoExit(false);
        $application->register('foo')->setCode(function () {
            throw new \InvalidArgumentException(sprintf('Dummy type "%s" is invalid.', \get_class(new class() {
            })));
        });
        $tester = new ApplicationTester($application);

        $tester->run(['command' => 'foo'], ['decorated' => false]);
        $this->assertContains('Dummy type "@anonymous" is invalid.', $tester->getDisplay(true));
    }

    public function testRenderExceptionStackTraceContainsRootException()
    {
        $application = new Application();
        $application->setAutoExit(false);
        $application->register('foo')->setCode(function () {
            throw new class('') extends \InvalidArgumentException {
            };
        });
        $tester = new ApplicationTester($application);

        $tester->run(['command' => 'foo'], ['decorated' => false]);
        $this->assertContains('[InvalidArgumentException@anonymous]', $tester->getDisplay(true));

        $application = new Application();
        $application->setAutoExit(false);
        $application->register('foo')->setCode(function () {
            throw new \InvalidArgumentException(sprintf('Dummy type "%s" is invalid.', \get_class(new class() {
            })));
        });
        $tester = new ApplicationTester($application);

        $tester->run(['command' => 'foo'], ['decorated' => false]);
        $this->assertContains('Dummy type "@anonymous" is invalid.', $tester->getDisplay(true));
    }

    public function testRun()
    {
        $application = new Application();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);
        $application->add($command = new \Foo1Command());
        $_SERVER['argv'] = ['cli.php', 'foo:bar1'];

        ob_start();
        $application->run();
        ob_end_clean();

        $this->assertInstanceOf('Symfony\Component\Console\Input\ArgvInput', $command->input, '->run() creates an ArgvInput by default if none is given');
        $this->assertInstanceOf('Symfony\Component\Console\Output\ConsoleOutput', $command->output, '->run() creates a ConsoleOutput by default if none is given');

        $application = new Application();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $this->ensureStaticCommandHelp($application);
        $tester = new ApplicationTester($application);

        $tester->run([], ['decorated' => false]);
        $this->assertStringEqualsFile(self::$fixturesPath.'/application_run1.txt', $tester->getDisplay(true), '->run() runs the list command if no argument is passed');

        $tester->run(['--help' => true], ['decorated' => false]);
        $this->assertStringEqualsFile(self::$fixturesPath.'/application_run2.txt', $tester->getDisplay(true), '->run() runs the help command if --help is passed');

        $tester->run(['-h' => true], ['decorated' => false]);
        $this->assertStringEqualsFile(self::$fixturesPath.'/application_run2.txt', $tester->getDisplay(true), '->run() runs the help command if -h is passed');

        $tester->run(['command' => 'list', '--help' => true], ['decorated' => false]);
        $this->assertStringEqualsFile(self::$fixturesPath.'/application_run3.txt', $tester->getDisplay(true), '->run() displays the help if --help is passed');

        $tester->run(['command' => 'list', '-h' => true], ['decorated' => false]);
        $this->assertStringEqualsFile(self::$fixturesPath.'/application_run3.txt', $tester->getDisplay(true), '->run() displays the help if -h is passed');

        $tester->run(['--ansi' => true]);
        $this->assertTrue($tester->getOutput()->isDecorated(), '->run() forces color output if --ansi is passed');

        $tester->run(['--no-ansi' => true]);
        $this->assertFalse($tester->getOutput()->isDecorated(), '->run() forces color output to be disabled if --no-ansi is passed');

        $tester->run(['--version' => true], ['decorated' => false]);
        $this->assertStringEqualsFile(self::$fixturesPath.'/application_run4.txt', $tester->getDisplay(true), '->run() displays the program version if --version is passed');

        $tester->run(['-V' => true], ['decorated' => false]);
        $this->assertStringEqualsFile(self::$fixturesPath.'/application_run4.txt', $tester->getDisplay(true), '->run() displays the program version if -v is passed');

        $tester->run(['command' => 'list', '--quiet' => true]);
        $this->assertSame('', $tester->getDisplay(), '->run() removes all output if --quiet is passed');
        $this->assertFalse($tester->getInput()->isInteractive(), '->run() sets off the interactive mode if --quiet is passed');

        $tester->run(['command' => 'list', '-q' => true]);
        $this->assertSame('', $tester->getDisplay(), '->run() removes all output if -q is passed');
        $this->assertFalse($tester->getInput()->isInteractive(), '->run() sets off the interactive mode if -q is passed');

        $tester->run(['command' => 'list', '--verbose' => true]);
        $this->assertSame(Output::VERBOSITY_VERBOSE, $tester->getOutput()->getVerbosity(), '->run() sets the output to verbose if --verbose is passed');

        $tester->run(['command' => 'list', '--verbose' => 1]);
        $this->assertSame(Output::VERBOSITY_VERBOSE, $tester->getOutput()->getVerbosity(), '->run() sets the output to verbose if --verbose=1 is passed');

        $tester->run(['command' => 'list', '--verbose' => 2]);
        $this->assertSame(Output::VERBOSITY_VERY_VERBOSE, $tester->getOutput()->getVerbosity(), '->run() sets the output to very verbose if --verbose=2 is passed');

        $tester->run(['command' => 'list', '--verbose' => 3]);
        $this->assertSame(Output::VERBOSITY_DEBUG, $tester->getOutput()->getVerbosity(), '->run() sets the output to debug if --verbose=3 is passed');

        $tester->run(['command' => 'list', '--verbose' => 4]);
        $this->assertSame(Output::VERBOSITY_VERBOSE, $tester->getOutput()->getVerbosity(), '->run() sets the output to verbose if unknown --verbose level is passed');

        $tester->run(['command' => 'list', '-v' => true]);
        $this->assertSame(Output::VERBOSITY_VERBOSE, $tester->getOutput()->getVerbosity(), '->run() sets the output to verbose if -v is passed');

        $tester->run(['command' => 'list', '-vv' => true]);
        $this->assertSame(Output::VERBOSITY_VERY_VERBOSE, $tester->getOutput()->getVerbosity(), '->run() sets the output to verbose if -v is passed');

        $tester->run(['command' => 'list', '-vvv' => true]);
        $this->assertSame(Output::VERBOSITY_DEBUG, $tester->getOutput()->getVerbosity(), '->run() sets the output to verbose if -v is passed');

        $application = new Application();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);
        $application->add(new \FooCommand());
        $tester = new ApplicationTester($application);

        $tester->run(['command' => 'foo:bar', '--no-interaction' => true], ['decorated' => false]);
        $this->assertSame('called'.PHP_EOL, $tester->getDisplay(), '->run() does not call interact() if --no-interaction is passed');

        $tester->run(['command' => 'foo:bar', '-n' => true], ['decorated' => false]);
        $this->assertSame('called'.PHP_EOL, $tester->getDisplay(), '->run() does not call interact() if -n is passed');
    }

    public function testRunWithGlobalOptionAndNoCommand()
    {
        $application = new Application();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);
        $application->getDefinition()->addOption(new InputOption('foo', 'f', InputOption::VALUE_OPTIONAL));

        $output = new StreamOutput(fopen('php://memory', 'w', false));
        $input = new ArgvInput(['cli.php', '--foo', 'bar']);

        $this->assertSame(0, $application->run($input, $output));
    }

    /**
     * Issue #9285.
     *
     * If the "verbose" option is just before an argument in ArgvInput,
     * an argument value should not be treated as verbosity value.
     * This test will fail with "Not enough arguments." if broken
     */
    public function testVerboseValueNotBreakArguments()
    {
        $application = new Application();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);
        $application->add(new \FooCommand());

        $output = new StreamOutput(fopen('php://memory', 'w', false));

        $input = new ArgvInput(['cli.php', '-v', 'foo:bar']);
        $application->run($input, $output);

        $this->addToAssertionCount(1);

        $input = new ArgvInput(['cli.php', '--verbose', 'foo:bar']);
        $application->run($input, $output);

        $this->addToAssertionCount(1);
    }

    public function testRunReturnsIntegerExitCode()
    {
        $exception = new \Exception('', 4);

        $application = $this->getMockBuilder('Symfony\Component\Console\Application')->setMethods(['doRun'])->getMock();
        $application->setAutoExit(false);
        $application->expects($this->once())
            ->method('doRun')
            ->willThrowException($exception);

        $exitCode = $application->run(new ArrayInput([]), new NullOutput());

        $this->assertSame(4, $exitCode, '->run() returns integer exit code extracted from raised exception');
    }

    public function testRunDispatchesIntegerExitCode()
    {
        $passedRightValue = false;

        // We can assume here that some other test asserts that the event is dispatched at all
        $dispatcher = new EventDispatcher();
        $dispatcher->addListener('console.terminate', function (ConsoleTerminateEvent $event) use (&$passedRightValue) {
            $passedRightValue = (4 === $event->getExitCode());
        });

        $application = new Application();
        $application->setDispatcher($dispatcher);
        $application->setAutoExit(false);

        $application->register('test')->setCode(function (InputInterface $input, OutputInterface $output) {
            throw new \Exception('', 4);
        });

        $tester = new ApplicationTester($application);
        $tester->run(['command' => 'test']);

        $this->assertTrue($passedRightValue, '-> exit code 4 was passed in the console.terminate event');
    }

    public functio