ode, 'array $args') !== false);

        $this->configureForInterface();
        $code = $this->pass->apply(
            'public function __call($method, array $args) {}',
            $this->mockedConfiguration
        );
        $this->assertTrue(\mb_strpos($code, '$method') !== false);
        $this->assertTrue(\mb_strpos($code, 'array $args') !== false);
    }

    protected function configureForClass(string $className = 'Mockery\Test\Generator\StringManipulation\Pass\MagicDummy')
    {
        $targetClass = DefinedTargetClass::factory($className);

        $this->mockedConfiguration
            ->shouldReceive('getTargetClass')
            ->andReturn($targetClass)
            ->byDefault();
        $this->mockedConfiguration
            ->shouldReceive('getTargetInterfaces')
            ->andReturn([])
            ->byDefault();
    }

    protected function configureForInterface(string $interfaceName = 'Mockery\Test\Generator\StringManipulation\Pass\MagicDummy')
    {
        $targetInterface = DefinedTargetClass::factory($interfaceName);

        $this->mockedConfiguration
            ->shouldReceive('getTargetClass')
            ->andReturn(null)
            ->byDefault();
        $this->mockedConfiguration
            ->shouldReceive('getTargetInterfaces')
            ->andReturn([$ta