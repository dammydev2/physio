
        $this->assertEquals([['with-default-name' => new ServiceClosureArgument(new TypedReference('with-default-name', NamedCommand::class))]], $commandLocator->getArguments());
        $this->assertSame([], $container->getParameter('console.command.ids'));

        $container = new ContainerBuilder();
        $container
            ->register('with-default-name', NamedCommand::class)
            ->setPublic(false)
            ->addTag('console.command', ['command' => 'new-name'])
        ;

        $pass->process($container);

        $this->assertSame(['new-name' => 'with-default-name'], $container->getDefinition('console.command_loader')->getArgument(1));
    }

    public function visibilityProvider()
    {
        return [
            [true],
            [false],
        ];
    }

    /**
     * @expec