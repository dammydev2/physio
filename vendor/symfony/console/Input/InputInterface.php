pace:name', $command->getName(), '->getName() returns the command name');
        $command->setName('foo');
        $this->assertEquals('foo', $command->getName(), '->setName() sets the command name');

        $ret = $command->setName('foobar:bar');
        $this->assertEquals($command, $ret, '->setName() implements a fluent interface');
        $this->assertEquals('foobar:bar', $command->getName(), '->setName() sets the command name');
    }

    /**
     * @dataProvider provideInvalidCommandNames
     */
    public function testInvalidCommandNames($name)
    {
        if (method_exists($this, 'expectException')) {
            $this->expectException('InvalidArgumentException');
            $this->expectExceptionMessage(sprintf('Command name "%s" is invalid.', $name));
        } else {
            $this->setExpectedException('InvalidArgumentException', sprintf('Command name "%s" is invalid.', $name));
        }

        $command = new \TestCommand();
        $command->setName($name);
    }

    public function provideInvalidCommandNames()
    {
        return [
            [''],
            ['foo:'],
        ];
    }

    public function testGetSetDescription()
    {
        $command = new \TestCommand();
        $this->assertEquals('description', $command->getDescription(), '->getDescription() returns the description');
        $ret = $command->setDescription('description1');
        $this->assertEquals($command, $ret, '->setDescription() implements a fluent interface');
        $this->assertEquals('description1', $command->getDescription(), '->setDescription() sets the description');
    }

    public function testGetSetHelp()
    {
        $command = new \TestCommand();
        $this->assertEquals('help', $command->getHelp(), '->getHelp() returns the help');
        $ret = $command->setHelp('help1');
        $this->assertEquals($command, $ret, '->setHelp() implements a fluent interface');
        $this->assertEquals('help1', $command->getHelp(), '->setHelp() sets the help');
        $command->setHelp('');
        $this->assertEquals('', $command->getHelp(), '->getHelp() does not fall back to the description');
    }

    public function testGetProcessedHelp()
    {
        $command = new \TestCommand();
        $command->setHelp('The %command.name% command does... Example: php %command.full_name%.');
        $this->assertContains('The namespace:name command does...', $command->getProcessedHelp(), '->getProcessedHelp() replaces %command.name% correctly');
        $this->assertNotContains('%command.full_name%', $command->getProcessedHelp(), '->getProcessedHelp() replaces %command.full_name%');

        $command = new \TestCommand();
        $command->setHelp('');
        $this->assertContains('description', $command->getProcessedHelp(), '->getProcessedHelp() falls back to the description');

        $command = new \TestCommand();
        $command->setHelp('The %command.name% command does... Example: php %command.full_name%.');
        $application = new Application();
        $application->add($command);
        $application->setDefaultCommand('namespace:name', true);
        $this->assertContains('The namespace:name command does...', $command->getProcessedHelp(), '->getProcessedHelp() replaces %command.name% correctly in single command applications');
        $this->assertNotContains('%command.full_name%', $command->getProcessedHelp(), '->getProcessedHelp() replaces %command.full_name% in single command applications');
    }

    public function testGetSetAliases()
    {
        $command = new \TestCommand();
        $this->assertEquals(['name'], $command->getAliases(), '->getAliases() returns the aliases');
        $ret = $command->setAliases(['name1']);
        $this->assertEquals($command, $ret, '->setAliases() implements a fluent interface');
        $this->assertEquals(['name1'], $command->getAliases(), '->setAliases() sets the aliases');
    }

    public function testSetAliasesNull()
    {
        $command = new \TestCommand();
        $this->{method_exists($this, $_ = 'expectException') ? $_ : 'setExpectedException'}('InvalidArgumentException');
        $command->setAliases(null);
    }

    public function testGetSynopsis()
    {
        $command = new \TestCommand();
        $command->addOption('foo');
        $command->addArgument('bar');
        $this->assertEquals('namespace:name [--foo] [--] [<bar>]', $command->getSynopsis(), '->getSynopsis() returns the synopsis');
    }

    public function testAddGetUsages()
    {
        $command = new \TestCommand();
        $command->addUsage('foo1');
        $command->addUsage('foo2');
        $this->assertContains('namespace:name foo1', $command->getUsages());
        $this->assertContains('namespace:name foo2', $command->getUsages());
    }

    public function testGetHelper()
    {
        $application = new Application();
        $command = new \TestCommand();
        $command->setApplication($application);
        $formatterHelper = new FormatterHelper();
        $this->assertEquals($f