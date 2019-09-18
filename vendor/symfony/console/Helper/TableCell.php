3Command', $application->find('foo3:bar'), '->find() returns the good command even if a namespace has same name');
        $this->assertInstanceOf('Foo4Command', $application->find('foo3:bar:toh'), '->find() returns a command even if its namespace equals another command name');
    }

    public function testFindCommandWithAmbiguousNamespacesButUniqueName()
    {
        $application = new Application();
        $application->add(new \FooCommand());
        $application->add(new \FoobarCommand());

        $this->assertInstanceOf('FoobarCommand', $application->find('f:f'));
    }

    public function testFindCommandWithMissingNamespace()
    {
        $application = new Application();
        $application->add(new \Foo4Command());

        $this->assertInstanceOf('Foo4Command', $application->find('f::t'));
    }

    /**
     * @dataProvider             provideInvalidCommandNamesSingle
     * @expectedException        \Symfony\Component\Console\Exception\CommandNotFoundException
     * @expectedExceptionMessage Did you mean this
     */
    public function testFindAlternativeExceptionMessageSingle($name)
    {
        $application = new Application();
        $application->add(new \Foo3Command());
        $application->find($name);
    }

    public function testDontRunAlternativeNamespaceName()
    {
        $application = new Application();
        $application->add(new \Foo1Command());
        $application->setAutoExit(false);
        $tester = new