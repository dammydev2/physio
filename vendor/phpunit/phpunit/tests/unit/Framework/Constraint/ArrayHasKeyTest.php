r ($i = 0; $i < $expectedNumberOfCalls; $i++) {
            $mock->bar('call_' . $i);
        }
    }

    public function testReturnTypesAreMockedCorrectly(): void
    {
        /** @var ClassWithAllPossibleReturnTypes|MockObject $stub */
        $stub = $this->createMock(ClassWithAllPossibleReturnTypes::class);

        $this->assertNull($stub->methodWithNoReturnTypeDeclaration());
        $this->assertSame('', $stub->methodWithStringReturnTypeDeclaration());
        $this->assertSame(0.0, $stub->methodWithFloatReturnTypeDeclaration());
        $this->assertSame(0, $stub->methodWithIntReturnTypeDeclaration());
        $this->assertFalse($stub->methodWithBoolReturnTypeDeclaration());
        $this->assertSame([], $stub->methodWithArrayReturnTypeDeclaration());
        $this->assertInstanceOf(MockObject::class, $stub->methodWithClassReturnTypeDeclaration());
    }

    public function testDisableAutomaticReturnValueGeneration(): void
    {
        $mock = $this->getMockBuilder(SomeClass::class)
            ->disableAutoReturnValueGeneration()
            ->getMock();

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(
            'Return value inference disabled and no expectation set up for SomeClass::doSomethingElse()'
        );

        $mock->doSomethingElse(1);
    }

    public function testDisableAutomaticReturnValueGenerationWithToString(): void
    {
        /** @var PHPUnit\Framework\MockObject\MockObject|StringableClass $mock */
        $mock = $this