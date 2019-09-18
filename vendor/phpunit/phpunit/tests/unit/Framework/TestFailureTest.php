edExactly(): void
    {
        $mock = $this->getMockBuilder(AnInterface::class)
                     ->getMock();

        $mock->expects($this->exactly(2))
             ->method('doSomething');

        $mock->doSomething();
        $mock->doSomething();
    }

    public function testStubbedException(): void
    {
        $mock = $this->getMockBuilder(AnInterface::class)
                     ->getMock();

        $mock->expects($this->any())
             ->method('doSomething')
             ->will($this->throwException(new \Exception));

        $this->expectException(\Exception::class);

        $mock->doSomething();
    }

    public function testStubbedWillThrowException(): void
    {
        $mock = $this->getMockBuilder(AnInterface::class)
                     ->getMock();

        $mock->expects($this->any())
             ->method('doSomething')
             ->willThrowException(new \Exception);

        $this->expectException(\Exception::class);

        $mock->doSomething();
    }

    public function testStubbedReturnValue(): void
    {
        $mock = $this->getMockBuilder(AnInterface::class)
                     ->getMock();

        $mock->expects($this->any())
             ->method('doSomething')
             ->will($this->returnValue('something'));

        $this->assertEquals('something', $mock->doSomething());

        $mock = $this->getMockBuilder(AnInterface::class)
                     ->getMock();

        $mock->expects($this->any())
             ->method('doSomething')
             ->willReturn('something');

        $this->assertEquals('something', $mock->doSomething());
    }

    public function testStubbedReturnValueMap(): void
    {
        $map = [
            ['a', 'b', 'c', 'd'],
            ['e', 'f', 'g', 'h'],
        ];

        $mock = $this->getMockBuilder(AnInterface::class)
                     ->getMock();

        $mock->expects($this->any())
             ->method('doSomething')
             ->will($this->returnValueMap($map));

        $this->assertEquals('d', $mock->doSomething('a', 'b', 'c'));
        $this->assertEquals('h', $mock->doSomething('e', 'f', 'g'));
        $this->assertNull($mock->doSomething('foo', 'bar'));

        $mock = $this->getMockBuilder(AnInterface::class)
                     ->getMock();

        $mock->expects($this->any())
             ->method('doSomething')
             ->willReturnMap($map);

        $this->assertEquals('d', $mock->doSomething('a', 'b', 'c'));
        $this->assertEquals('h', $mock->doSomething('e', 'f', 'g'));
        $this->assertNull($mock->doSomething('foo', 'bar'));
    }

    public function testStubbedReturnArgument(): void
    {
        $mock = $this->getMockBuilder(AnInterface::class)
                     ->getMock();

        $mock->expects($this->any())
             ->method('doSomething')
             ->will($this->returnArgument(1));

        $this->assertEquals('b', $mock->doSomething('a', 'b'));

        $mock = $this->getMockBuilder(AnInterface::class)
                     ->getMock();

        $mock->expects($this->any())
             ->method('doSomething')
             ->willReturnArgument(1);

        $this->assertEquals('b', $mock->doSomething('a', 'b'));
    }

    public function testFunctionCallback(): void
    {
        $mock = $this->getMockBuilder(SomeClass::class)
                     ->setMethods(['doSomething'])
                     ->getMock();

        $mock->expects($this->once())
             ->method('doSomething')
             ->will($this->returnCallback('FunctionCallbackWrapper::functionCallback'));

        $this->assertEquals('pass', $mock->doSomething('foo', 'bar'));

        $mock = $this->getMockBuilder(SomeClass::class)
                     ->setMethods(['doSomething'])
                     ->getMock();

        $mock->expects($this->once())
             ->method('doSomething')
             ->willReturnCallback('FunctionCallbackWrapper::functionCallback');

        $this->assertEquals('pass', $mock->doSomething('foo', 'bar'));
    }

    public function testStubbedReturnSelf(): void
    {
        $mock = $this->getMockBuilder(AnInterface::class)
                     ->getMock();

        $mock->expects($this->any())
             ->method('doSomething')
             ->will($this->returnSelf());

        $this->assertEquals($mock, $mock->doSomething());

        $mock = $this->getMockBuilder(AnInterface::class)
                     ->getMock();

        $mock->expects($this->any())
        