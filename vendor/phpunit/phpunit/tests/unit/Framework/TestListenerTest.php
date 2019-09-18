a = $this->getMockBuilder(stdClass::class)
                  ->getMock();

        $b = $this->getMockBuilder(stdClass::class)
                  ->getMock();

        $this->assertEquals($a, $b);
    }

    public function testMockObjectsConstructedIndepentantlyShouldNotBeTheSame(): void
    {
        $a = $this->getMockBuilder(stdClass::class)
                  ->getMock();

        $b = $this->getMockBuilder(stdClass::class)
                  ->getMock();

        $this->assertNotSame($a, $b);
    }

    public function testClonedMockObjectCanBeUsedInPlaceOfOriginalOne(): void
    {
        $x = $this->getMockBuilder(stdClass::class)
                  ->getMock();

        $y = clone $x;

        $mock = $this->getMockBuilder(stdClass::class)
                     ->setMethods(['foo'])
                     ->getMock();

        $mock->expects($this->once())
             ->method('foo')
             ->with($this->equalTo($x));

        $mock->foo($y);
    }

    public function testClonedMockObjectIsNotIdenticalToOriginalOne(): void
    {
        $x = $this->getMockBuilder(stdClass::class)
                  ->getMock();

        $y = clone $x;

        $mock = $this->getMockBuilder(stdClass::class)
                     ->setMethods(['foo'])
                     ->getMock();

        $mock->expects($this->once())
             ->method('foo')
             ->wit