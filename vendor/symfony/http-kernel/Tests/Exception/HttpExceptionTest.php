fo('{Message {nothing} {user} {foo.bar} a}', ['user' => 'Bob', 'foo.bar' => 'Bar']);

        $expected = ['[info] {Message {nothing} Bob Bar a}'];
        $this->assertLogsMatch($expected, $this->getLogs());
    }

    public function testObjectCastToString()
    {
        if (method_exists($this, 'createPartialMock')) {
            $dummy = $this->createPartialMock(DummyTest::class, ['__toString']);
        } else {
            $dummy = $this->getMock(DummyTest::class, ['__toString']);
        }
        $dummy->expects($this->atLeastOnce())
            ->method('__toString')
            ->will($this->returnValue('DUMMY'));

        $this->logger->warning($dummy);

        $expected = ['[warning] DUMMY'];
        $this->assertLogsMatch($expected, $this->getLogs());
    }

    public function testContextCanContainAnything()
    {
        $context = [
            'bool' => true,
            'null' => null,
            'string' => 'Foo',
            'int' => 0,
            'float' => 0.5,
            'nested' => ['with object' => new DummyTest()],
            'object' => new \DateTime(),
            'resource' => fopen('php://memory', 'r'),
        ];

        $this->logger->warning('Crazy context data', $context);

        $expected = ['[warning] Crazy co