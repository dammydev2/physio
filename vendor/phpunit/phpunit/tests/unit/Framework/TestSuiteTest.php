try {
            $mock->right();
            $this->fail('Expected exception');
        } catch (ExpectationFailedException $e) {
            $this->assertSame(
                'SomeClass::right() was not expected to be called.',
                $e->getMessage()
            );
        }

        $this->resetMockObjects();
    }

    public function testWithAnythingInsteadOfWithAnyParameters(): void
    {
        $mock = $this->getMockBuilder(SomeClass::class)
                     ->setMethods(['right', 'wrong'])
                     ->getMock();

        $mock->expects($this->once())
             ->method('right')
             ->with($this->anything());

        try {
            $mock->right();
            $this->fail('Expected exception');
        } catch (ExpectationFailedException $e) {
            $this->assertSame(
                "Expectation failed for method name is equal to 'right' when invoked 1 time(s)\n" .
                'Parameter count for invocation SomeClass::right() is too low.' . "\n" .
                'To allow 0 or more parameters with any value, omit ->with() or use ->withAnyParameters() instead.',
                $e->getMessage()
            );
        }

        $this->resetMockObjects();
    }

    /**
     * See https://github.com/sebastianbergmann/phpunit-mock-objects/issues/81
     */
    public function testMockArgumentsPassedByReference(): void
    {
        $foo = $this->getMockBuilder('MethodCallbackByReference')
                    ->setMethods(['bar'])
                    ->disableOriginalConstructor()
                    ->disableArgumentCloning()
                    ->getMock();

        $foo->expects($this->any())
            ->method('bar')
            ->will($this->returnCallback([$foo, 'callback']));

        $a = $b = $c = 0;

        $foo->bar($a, $b, $c);

        $this->assertEquals(1, $b);
    }

    /**
     * See https://github.com/sebastianbergmann/phpunit-mock-objects/issues/81
     */
    public function testMockArgumentsPassedByReference2(): void
    {
        $foo = $this->getMockBuilder('MethodCallbackByReference')
                    ->disableOriginalConstructor()
                    ->disableArgumentCloning()
                    ->getMock();

        $foo->expects($this->any())
            ->method('bar')
            ->will($this->returnCallback(
                function (&$a, &$b, $c): void {
                    $b = 1;
                }
            ));

        $a = $b = $c = 0;

        $foo->bar($a, $b, $c);

        $this->assertEquals(1, $b);
    }

    /**
     * @see https://github.com/sebastianbergmann/phpunit-mock-objects/issues/116
     */
    public function testMockArgumentsPassedByReference3(): void
    {
        $foo = $this->getMockBuilder('MethodCallbackByReference')
                    ->setMethods(['bar'])
                    ->disableOriginalConstructor()
                    ->disableArgumentCloning()
                    ->getMock();

        $a = new stdClass;
        $b = $c = 0;

        $foo->expects($this->any())
            ->method('bar')
            ->with($a, $b, $c)
            ->will($this->returnCallback([$foo, 'callback']));

        $this->assertNull($foo->bar($a, $b, $c));
    }

    /**
     * @see https://github.com/sebastianbergmann/phpunit/issues/796
     */
    public function testMockArgumentsPassedByReference4(): void
    {
        $foo = $this->getMockBuilder('MethodCallbackByReference')
                    ->setMethods(['bar'])
                    ->disableOriginalConstructor()
                    ->disableArgumentCloning()
                    ->getMock();

        $a = new stdClass;
        $b = $c = 0;

        $foo->expects($this->any())
            ->method('bar')
            ->with($this->isInstanceOf(stdClass::class), $b, $c)
            ->will($this->returnCallback([$foo, 'callback']));

        $this->assertNull($foo->bar($a, $b, $c));
    }

    /**
     * @requires extension soap
     */
    public function testCreateMockFromWsdl(): void
    {
        $mock = $this->getMockFromWsdl(TEST_FILES_PATH . 'GoogleSearch.wsdl', 'WsdlMock');

        $this->assertStringStartsWith(
            'Mock_WsdlMock_',
            \get_class($mock)
        );
    }

    /**
     * @requires extension soap
     */
    public function testCreateNamespacedMockFromWsdl(): void
    {
        $mock = $this->getMockFromWsdl(TEST_FILES_PATH . 'GoogleSearch.wsdl', 'My\\Space\\WsdlMock');

        $this->assertStringStartsWith(
            'Mock_WsdlMock_',
            \get_class($mock)
        );
    }

    /**
     * @requires extension soap
     */
    public function testCreateTwoMocksOfOneWsdlFile(): void
    {
        $a = $this->getMockFromWsdl(TEST_FILES_PATH . 'GoogleSearch.wsdl');
        $b = $this->getMockFromWsdl(TEST_FILES_PATH . 'GoogleSearch.wsdl');

        $this->assertStringStartsWith('Mock_GoogleSearch_', \get_class($a));
        $this->assertEquals(\get_class($a), \get_class($b));
    }

    /**
     * @see      https://github.com/sebastianbergmann/phpunit/issues/2573
     * @ticket   2573
     * @requires extension soap
     */
    public function testCreateMockOfWsdlFileWithSpecialChars(): void
    {
        $mock = $this->getMockFromWsdl(TEST_FILES_PATH . 'Go ogle-Sea.rch.wsdl');

        $this->assertStringStartsWith('Mock_GoogleSearch_', \get_class($mock));
    }

    /**
     * @see    https://github.com/sebastianbergmann/phpunit-mock-objects/issues/156
     * @ticket 156
     */
    public function testInterfaceWithStaticMethodCanBeStubbed(): void
    {
        $this->assertInstanceOf(
            InterfaceWithStaticMethod::class,
            $this->getMockBuilder(InterfaceWithStaticMethod::class)->getMock()
        );
    }

    public function testInvokingStubbedStaticMethodRaisesException(): void
    {
        $mock = $this->getMockBuilder(ClassWithStaticMethod::class)->getMock();

        $this->expectException(\PHPUnit\Framework\MockObject\BadMethodCallException::class);

        $mock->staticMethod();
    }

    /**
     * @see    https://github.com/sebastianbergmann/phpunit-mock-objects/issues/171
     * @ticket 171
     */
    public function testStubForClassThatImplementsSerializableCanBeCreatedWithoutInvokingTheConstructor(): void
    {
        $this->assertInstanceOf(
            ClassThatImplementsSerializable::class,
            $this->getMockBuilder(ClassThatImplementsSerializable::class)
                 ->disableOriginalConstructor()
                 ->getMock()
        );
    }

    public function testGetMockForClassWithSelfTypeHint(): void
    {
        $this->assertInstanceOf(
            ClassWithSelfTypeHint::class,
            $this->getMockBuilder(ClassWithSelfTypeHint::class)->getMock()
        );
    }

    public function testStringableClassDoesNotThrow(): void
    {
        /** @var PHPUnit\Framework\MockObject\MockObject|StringableClass $mock */
        $mock = $this->getMockBuilder(StringableClass::class)->getMock();

        $this->assertIsString((string) $mock);
    }

    public function testStringableClassCanBeMocked(): void
    {
        /** @var PHPUnit\Framework\MockObject\MockObject|StringableClass $mock */
        $mock = $this->getMockBuilder(StringableClass::class)->getMock();

        $mock->method('__toString')->willReturn('foo');

        $this->assertSame('foo', (string) $mock);
    }

    public function traversableProvider(): array
    {
        return [
            ['Traversable'],
            ['\Traversable'],
