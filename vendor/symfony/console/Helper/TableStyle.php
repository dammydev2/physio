Exception if command does not exist, with alternatives');
            $this->assertRegExp('/Did you mean one of these/', $e->getMessage(), '->find() throws a CommandNotFoundException if command does not exist, with alternatives');
            $this->assertRegExp('/foo1:bar/', $e->getMessage());
            $this->assertRegExp('/foo:bar/', $e->getMessage());
        }

        // Namespace + plural
        try {
            $application->find('foo2:bar');
            $this->fail('->find() throws a CommandNotFoundException if command does not exist, with alternatives');
        } catch (\Exception $e) {
            $this->assertInstanceOf('Symfony\Component\Console\Exception\CommandNotFoundException', $e, '->find() throws a CommandNotFoundException if command does not exist, with alternatives');
            $this->assertRegExp('/Did you mean one of these/', $e->getMessage(), '->find() throws a CommandNotFoundException if command does not exist, with alternatives');
            $this->assertRegExp('/foo1/', $e->getMessage());
        }

        $application->add(new \Foo3Command());
        $application->add(new \Foo4Command());

        // Subnamespace + plural
        try {
            $a = $application->find('foo3:');
            $this->fail('->find() should throw an Symfony\Component\Console\Exception\CommandNotFoundException if a command is ambiguous because of a subnamespace, with alternatives');
        } catch (\Exception $e) {
            $this->assertInstanceOf('Symfony\Component\Console\Exception\CommandNotFoundException', $e);
            $this->assertRegExp('/foo3:bar/', $e->getMessage());
            $this->assertRegExp('/foo3:bar:toh/', $e->getMessage());
        }
    }

    public function testFindAlternativeCommands()
    {
        $application = new Application();

        $application->add(new \FooCommand());
        $application->add(new \Foo1Command());
        $application->add(new \Foo2Command());

        try {
            $application->find($commandName = 'Unknown command');
            $this->fail('->find() throws a CommandNotFoundException if command does not exist');
        } catch (\Exception $e) {
            $this->assertInstanceOf('Symfony\Component\Console\Exception\CommandNotFoundException', $e, '->find() throws a CommandNotFoundException if command does not exist');
            $this->assertSame([], $e->getAlternatives());
            $this->assertEquals(sprintf('Command "%s" is not defined.', $commandName), $e->getMessage(), '->find() throws a CommandNotFoundException if command does not exist, without alternatives');
        }

        // Test if "bar1" command throw a "CommandNotFoundException" and does not contain
        // "foo:bar" as alternative because "bar1" is too far from "foo:bar"
        try {
            $application->find($commandName = 'bar1');
            $this->fail('->find() throws a CommandNotFoundException if command does not exist');
        } catch (\Exception $e) {
            $this->assertInstanceOf('Symfony\Component\Console\Exception\CommandNotFoundException', $e, '->find() throws a CommandNotFoundException if command does not exist');
            $this->assertSame(['afoobar1', 'foo:bar1'], $e->getAlternatives());
            $this->assertRegExp(sprintf('/Command "%s" is not defined./', $commandName), $e->getMessage(), '->find() throws a CommandNotFoundException if command does not exist, with alternatives');
            $this->assertRegExp('/afoobar1/', $e->getMessage(), '->find() throws a CommandNotFoundException if command does not exist, with alternative : "afoobar1"');
            $this->assertRegExp('/foo:bar1/', $e->getMessage(), '->find() throws a CommandNotFoundException if command does not exist, with alternative : "foo:bar1"');
            $this->assertNotRegExp('/foo:bar(?>!1)/', $e->getMessage(), '->find() throws a CommandNotFoundException if command does not exist, without "foo:bar" alternative');
        }
    }

    public function testFindAlternativeCommandsWithAnAlias()
    {
        $fooCommand = new \FooCommand();
        $fooCommand->setAliases(['foo2']);

        $application = new Application();
        $application->add($fooCommand);

        $result = $application->find('foo');

        $this->assertSame($fooCommand, $result);
    }

    public function testFindAlternativeNamespace()
    {
        $application = new Application();

        $application->add(new \FooCommand());
        $application->add(new \Foo1Command());
        $application->add(new \Foo2Command());
        $application->add(new \Foo3Command());

        try {
            $application->find('Unknown-namespace:Unknown-command');
            $this->fail('->find() throws a CommandNotFoundException if namespace does not exist');
        } catch (\Exception $e) {
            $this->assertInstanceOf('Symfony\Component\Console\Exception\CommandNotFoundException', $e, '->find() throws a CommandNotFoundException if namespace does not exist');
            $this->assertSame([], $e->getAlternatives());
            $this->assertEquals('There are no commands defined in the "Unknown-namespace" namespace.', $e->getMessage(), '->find() throws a CommandNotFoundException if namespace does not exist, without alternatives');
        }

        try {
            $application->find('foo2:command');
            $this->fail('->find() throws a CommandNotFoundException if namespace does not exist');
        } catch (\Exception $e) {
            $this->assertInstanceOf('Symfony\Component\Console\Exception\NamespaceNotFoundException', $e, '->find() throws a NamespaceNotFoundException if namespace does not exist');
            $this->assertInstanceOf('Symfony\Component\Console\Exception\CommandNotFoundException', $e, 'NamespaceNotFoundException extends from CommandNotFoundException');
            $this->assertCount(3, $e->getAlternatives());
            $this->assertContains('foo', $e->getAlternatives());
            $this->assertContains('foo1', $e->getAlternatives());
            $this->assertContains('foo3', $e->getAlternatives());
            $this->assertRegExp('/There are no commands defined in the "foo2" namespace./', $e->getMessage(), '->find() throws a CommandNotFoundException if namespace does not exist, with alternative');
            $this->assertRegExp('/foo/', $e->getMessage(), '->find() throws a CommandNotFoundException if namespace does not exist, with alternative : "foo"');
            $this->assertRegExp('/foo1/', $e->getMessage(), '->find() throws a CommandNotFoundException if namespace does not exist, with alternative : "foo1"');
            $this->assertRegExp('/foo3/', $e->getMessage(), '->find() throws a CommandNotFoundException if namespace does not exist, with alternative : "foo3"');
        }
    }

    public function testFindAlternativesOutput()
    {
        $application = new Application();

        $application->add(new \FooCommand());
        $application->add(new \Foo1Command());
        $application->add(new \Foo2Command());
        $application->add(new \Foo3Command());

        $expectedAlternatives = [
            'afoobar',
            'afoobar1',
            'afoobar2',
            'foo1:bar',
            'foo3:bar',
            'foo:bar',
            'foo:bar1',
        ];

        try {
            $application->find('foo');
            $this->fail('->find() throws a CommandNotFoundException if command is not defined');
        } catch (\Exception $e) {
            $this->assertInstanceOf('Symfony\Component\Console\Exception\CommandNotFoundException', $e, '->find() throws a CommandNotFoundException if command is not defined');
            $this->assertSame($expectedAlternatives, $e->getAlternatives());

            $this->assertRegExp('/Command "foo" is not defined\..*Did you mean one of these\?.*/Ums', $e->getMessage());
        }
    }

    public function testFindNamespaceDoesNotFailOnDeepSimilarNamespaces()
    {
        $application = $this->getMockBuilder('Symfony\Component\Console\Application')->setMethods(['getNamespaces'])->getMock();
        $application->expects($this->once())
            ->method('getNamespaces')
            ->will($this->returnValue(['foo:sublong', 'bar:sub']));

        $this->assertEquals('foo:sublong', $application->findNamespace('f:sub'));
    }

    /**
     * @expectedException \Symfony\Component\Console\Exception\CommandNotFoundException
     * @expectedExceptionMessage Command "foo::bar" is not defined.
     */
    public function testFindWithDoubleColonInNameThrowsException()
    {
        $application = new Application();
        $application->add(new \FooCommand());
        $application->add(new \Foo4Command());
        $application->find('foo::bar');
    }

    public function testSetCatchExceptions()
    {
        $application = new Application();
        $application->setAutoExit(false);
        putenv('COLUMNS=120');
        $tester = new ApplicationTester($application);

        $application->setCatchExceptions(true);
        $this->assertTrue($application->areExceptionsCaught());

        $tester->run(['command' => 'foo'], ['decorated' => false]);
        $this->assertStringEqualsFile(self::$fixturesPath.'/application_renderexception1.txt', $tester->getDisplay(true), '->setCatchExceptions() sets the catch exception flag');

        $tester->run(['command' => 'foo'], ['decorated' => false, 'capture_stderr_separately' => true]);
        $this->assertStringEqualsFile(self::$fixturesPath.'/application_renderexception1.txt', $tester->getErrorOutput(true), '->setCatchExceptions() sets the catch exception flag');
        $this->assertSame('', $tester->getDisplay(true));

        $application->setCatchExceptions(false);
        try {
            $tester->run(['command' => 'foo'], ['decorated' => false]);
            $this->fail('->setCatchExceptions() sets the catch exception flag');
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Exception', $e, '->setCatchExceptions() sets the catch exception flag');
            $this->assertEquals('Command "foo" is not defined.', $e->getMessage(), '->setCatchExceptions() sets the catch exception flag');
        }
    }

    public function testAutoExitSetting()
    {
        $application = new Application();
        $this->assertTrue($application->isAutoExitEnabled());

        $application->setAutoExit(false);
        $this->assertFalse($application->isAutoExitEnabled());
    }

    public function testRenderException()
    {
        $application = new Application();
        $application->setAutoExit(false);
        putenv('COLUMNS=120');
        $tester = new ApplicationTester($application);

        $tester->run(['command' => 'foo'], ['decorated' => false, 'capture_stderr_separately' => true]);
        $this->assertStringEqualsFile(self::$fixturesPath.'/application_renderexception1.txt', $tester->getErrorOutput(true), '->renderException() renders a pretty exception');

        $tester->run(['command' => 'foo'], ['decorated' => false, 'verbosity' => Output::VERBOSITY_VERBOSE, 'capture_stderr_separately' => true]);
        $this->assertContains('Exception trace', $tester->getErrorOutput(), '->renderException() renders a pretty exception with a stack trace when verbosity is verbose');

        $tester->run(['command' => 'list', '--foo' => true], ['decorated' => false, 'capture_stderr_separately' => true]);
        $this->assertStringEqualsFile(self::$fixturesPath.'/application_renderexception2.txt', $tester->getErrorOutput(true), '->renderException() renders the command synopsis when an exception occurs in the context of a command');

        $application->add(new \Foo3Command());
        $tester = new ApplicationTester($application);
        $tester->run(['command' => 'foo3:bar'], ['decorated' => false, 'capture_stderr_separately' => true]);
        $this->assertStringEqualsFile(self::$fixturesPath.'/application_renderexception3.txt', $tester->getErrorOutput(true), '->renderException() renders a pretty exceptions with previous exceptions');

        $tester->run(['command' => 'foo3:bar'], ['decorated' => false, 'verbosity' => Output::VERBOSITY_VERBOSE]);
        $this->assertRegExp('/\[Exception\]\s*First exception/', $tester->getDisplay(), '->renderException() renders a pretty exception without code exception when code exception is default and verbosity is verbose');
        $this->assertRegExp('/\[Exception\]\s*Second exception/', $tester->getDisplay(), '->renderException() renders a pretty exception without code exception when code exception is 0 and verbosity is verbose');
        $this->assertRegExp('/\[Exception \(404\)\]\s*Third exception/', $tester->getDisplay(), '->renderException() renders a pretty exception with code exception when code exception is 404 and verbosity is verbose');

        $tester->run(['command' => 'foo3:bar'], ['decorated' => true]);
        $this->assertStringEqualsFile(self::$fixturesPath.'/application_renderexception3decorated.txt', $tester->getDisplay(true), '->renderException() renders a pretty exceptions with previous exceptions');

        $tester->run(['command' => 'foo3:bar'], ['decorated' => true, 'capture_stderr_separately' => true]);
        $this->assertStringEqualsFile(self::$fixturesPath.'/application_renderexception3decorated.txt', $tester->getErrorOutput(true), '->renderException() renders a pretty exceptions with previous exceptions');

        $application = new Application();
        $application->setAutoExit(false);
        putenv('COLUMNS=32');
        $tester = new ApplicationTester($application);

        $tester->run(['command' => 'foo'], ['decorated' => false,  'capture_stderr_separately' => true]);
        $this->assertStringEqualsFile(self::$fixturesPath.'/application_renderexception4.txt', $tester->getErrorOutput(true), '->renderException() wraps messages when they are bigger than the terminal');
        putenv('COLUMNS=120');
    }

    public function testRenderExceptionWithDoubleWidthCharacters()
    {
        $application = new Application();
        $application->setAutoExit(false);
        putenv('COLUMNS=120');
        $application->register('foo')->setCode(function () {
            throw new \Exception('エラーメッセージ');
        });
        $tester = new ApplicationTester($application);

        $tester->run(['command' => 'foo'], ['decorated' => false, 'capture_stderr_separately' => true]);
        $this->assertStringMatchesFormatFile(self::$fixturesPath.'/application_renderexception_doublewidth1.txt', $tester->getErrorOutput(true), '->renderException() renders a pretty exceptions with previous exceptions');

        $tester->run(['command' => 'foo'], ['decorated' => true, 'capture_stderr_separately' => true]);
        $this->assertStringMatchesFormatFile(self::$fixturesPath.'/application_renderexception_doublewidth1decorated.txt', $tester->getErrorOutput(true), '->renderException() renders a pretty exceptions with previous exceptions');

        $application = new Application();
        $application->setAutoExit(false);
        putenv('COLUMNS=32');
        $application->register('foo')->setCode(function () {
            throw new \Exception('コマンドの実行中にエラーが発生しました。');
        });
        $tester = new ApplicationTester($application);
        $tester->run(['command' => 'foo'], ['decorated' => false, 'capture_stderr_separately' => true]);
        $this->assertStringMatchesFormatFile(self::$fixturesPath.'/application_renderexception_doublewidth2.txt', $tester->getErro