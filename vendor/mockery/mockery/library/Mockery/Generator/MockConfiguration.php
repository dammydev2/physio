tTrue((bool) preg_match("/stdClass/", $e->getMessage()));
            $this->assertTrue((bool) preg_match("/ArrayAccess/", $e->getMessage()));
            $this->assertTrue((bool) preg_match("/Countable/", $e->getMessage()));
        }
    }

    public function testNamedMockWithConstructorArgs()
    {
        $m = mock("MockeryTest_ClassConstructor2[foo]", array($param1 = new stdClass()));
        $m->shouldReceive("foo")->andReturn(123);
        $this->assertEquals(123, $m->foo());
        $this->assertEquals($param1, $m->getParam1());
    }

    public function testNamedMockWithConstructorArgsAndArrayDefs()
    {
        $m = mock(
            "MockeryTest_ClassConstructor2[foo]",
            array($param1 = new stdClass()),
            array("foo" => 123)
        );
        $this->assertEquals(123, $m->foo());
        $this->assertEquals($param1, $m->getParam1());
    }

    public function testNamedMockWithConstructorArgsWithInternalCallToMockedMethod()
    {
        $m = mock("MockeryTest_ClassConstructor2[foo]", array($param1 = new stdClass()));
        $m->shouldReceive("foo")->andReturn(123);
        $this->assertEquals(123, $m->bar());
    }

    public function testNamedMockWithConstructorArgsButNoQuickDefsShouldLeaveConstructorIntact()
    {
        $m = mock("MockeryTest_ClassConstructor2", array($param1 = new stdClass()));
        $m->makePartial();
        $this->assertEquals($param1, $m->getParam1());
    }

    public function testNamedMockWithMakePartial()
    {
        $m = mock("MockeryTest_ClassConstructor2", array($param1 = new stdClass()));
        $m->makePartial();
        $this->assertEquals('foo', $m->bar());
        $m->shouldReceive("bar")->andReturn(123);
        $this->assertEquals(123, $m->bar());
    }

    public function testNamedMockWithMakePartialThrowsIfNotAvailable()
    {
        $m = mock("MockeryTest_ClassConstructor2", array($param1 = new stdClass()));
        $m->makePartial();
        $this->expectException(\BadMethodCallException::class);
        $m->foorbar123();
        $m->mockery_verify();
    }

    public function testMockingAKnownConcreteClassSoMockInheritsClassType()
    {
        $m = mock('stdClass');
        $m->shouldReceive('foo')->andReturn('bar');
        $this->assertEquals('bar', $m->foo());
        $this->assertInstanceOf(stdClass::class, $m);
    }

    public function testMockingAKnownUserClassSoMockInheritsClassType()
    {
        $m = mock('MockeryTest_TestInheritedType');
        $this->assertInstanceOf(MockeryTest_TestInheritedType::class, $m);
    }

    public function testMockingAConcreteObjectCreatesAPartialWithoutError()
    {
        $m = mock(new stdClass);
        $m->shouldReceive('foo')->andReturn('bar');
        $this->assertEquals('bar', $m->foo());
        $this->assertInstanceOf(stdClass::class, $m);
    }

    public function testCreatingAPartialAllowsDynamicExpectationsAndPassesThroughUnexpectedMethods()
    {
        $m = mock(new MockeryTestFoo);
        $m->shouldReceive('bar')->andReturn('bar');
        $this->assertEquals('bar', $m->bar());
        $this->assertEquals('foo', $m->foo());
        $this->assertInstanceOf(MockeryTestFoo::class, $m);
    }

    public function testCreatingAPartialAllowsExpectationsToInterceptCallsToImplementedMethods()
    {
        $m = mock(new MockeryTestFoo2);
        $m->shouldReceive('bar')->andReturn('baz');
        $this->assertEquals('baz', $m->bar());
        $this->assertEquals('foo', $m->foo());
        $this->assertInstanceOf(MockeryTestFoo2::class, $m);
    }

    public function testBlockForwardingToPartialObject()
    {
        $m = mock(new MockeryTestBar1, array('foo'=>1, Mockery\Container::BLOCKS => array('method1')));
        $this->assertSame($m, $m->method1());
    }

    public function testPartialWithArrayDefs()
    {
        $m = mock(new MockeryTestBar1, array('foo'=>1, Mockery\Container::BLOCKS => array('method1')));
        $this->assertEquals(1, $m->foo());
    }

    public function testPassingClosureAsFinalParameterUsedToDefineExpectations()
    {
        $m = mock('foo', function ($m) {
            $m->shouldReceive('foo')->once()->andReturn('bar');
        });
        $this->assertEquals('bar', $m->foo());
    }

    public function testMockingAKnownConcreteFinalClassThrowsErrors_OnlyPartialMocksCanMockFinalElements()
    {
        $this->expectException(\Mockery\Exception::class);
        $m = mock('MockeryFoo3');
    }

    public function testMockingAKnownConcreteClassWithFinalMethodsThrowsNoException()
    {
        $this->assertInstanceOf(MockInterface::class, mock('MockeryFoo4'));
    }

    /**
     * @group finalclass
     */
    public function testFinalClassesCanBePartialMocks()
    {
        $m = mock(new MockeryFoo3);
        $m->shouldReceive('foo')->andReturn('baz');
        $this->assertEquals('baz', $m->foo());
        $this->assertNotInstanceOf(MockeryFoo3::class, $m);
    }

    public function testSplClassWithFinalMethodsCanBeMocked()
    {
        $m = mock('SplFileInfo');
        $m->shouldReceive('foo')->andReturn('baz');
        $this->assertEquals('baz', $m->foo());
        $this->assertInstanceOf(SplFileInfo::class, $m);
    }

    public function testSplClassWithFinalMethodsCanBeMockedMultipleTimes()
    {
        mock('SplFileInfo');
        $m = mock('SplFileInfo');
        $m->shouldReceive('foo')->andReturn('baz');
        $this->assertEquals('baz', $m->foo());
        $this->assertInstanceOf(SplFileInfo::class, $m);
    }

    public function testClassesWithFinalMethodsCanBeProxyPartialMocks()
    {
        $m = mock(new MockeryFoo4);
        $m->shouldReceive('foo')->andReturn('baz');
        $this->assertEquals('baz', $m->foo());
        $this->assertEquals('bar', $m->bar());
        $this->assertInstanceOf(MockeryFoo4::class, $m);
    }

    public function testClassesWithFinalMethodsCanBeProperPartialMocks()
    {
        $m = mock('MockeryFoo4[bar]');
        $m->shouldReceive('bar')->andReturn('baz');
        $this->assertEquals('baz', $m->foo());
        $this->assertEquals('baz', $m->bar());
        $this->assertInstanceOf(MockeryFoo4::class, $m);
    }

    public function testClassesWithFinalMethodsCanBeProperPartialMocksButFinalMethodsNotPartialed()
    {
        $m = mock('MockeryFoo4[foo]');
        $m->shouldReceive('foo')->andReturn('foo');
        $this->assertEquals('baz', $m->foo()); // partial expectation ignored - will fail callcount assertion
        $this->assertInstanceOf(MockeryFoo4::class, $m);
    }

    public function testSplfileinfoClassMockPassesUserExpectations()
    {
        $file = mock('SplFileInfo[getFilename,getPathname,getExtension,getMTime]', array(__FILE__));
        $file->shouldReceive('getFilename')->once()->andReturn('foo');
        $file->shouldReceive('getPathname')->once()->andReturn('path/to/foo');
        $file->shouldReceive('getExtension')->once()->andReturn('css');
        $file->shouldReceive('getMTime')->once()->andReturn(time());

        // not sure what this test is for, maybe something special about
        // SplFileInfo
        $this->assertEquals('foo', $file->getFilename());
        $this->assertEquals('path/to/foo', $file->getPathname());
        $this->assertEquals('css', $file->getExtension());
        $this->assertTrue(is_int($file->getMTime()));
    }

    public function testCanMockInterface()
    {
        $m = mock('MockeryTest_Interface');
        $this->assertInstanceOf(MockeryTest_Interface::class, $m);
    }

    public function testCanMockSpl()
    {
        $m = mock('\\SplFixedArray');
        $this->assertInstanceOf(SplFixedArray::class, $m);
    }

    public function testCanMockInterfaceWithAbstractMethod()
    {
        $m = mock('MockeryTest_InterfaceWithAbstractMethod');
        $this->assertInstanceOf(MockeryTest_InterfaceWithAbstractMethod::class, $m);
        $m->shouldReceive('foo')->andReturn(1);
        $this->assertEquals(1, $m->foo());
    }

    public function testCanMockAbstractWithAbstractProtectedMethod()
    {
        $m = mock('MockeryTest_AbstractWithAbstractMethod');
        $this->assertInstanceOf(MockeryTest_AbstractWithAbstractMethod::class, $m);
    }

    public function testCanMockInterfaceWithPublicStaticMethod()
    {
        $m = mock('MockeryTest_InterfaceWithPublicStaticMethod');
        $this->assertInstanceOf(MockeryTest_InterfaceWithPublicStaticMethod::class, $m);
    }

    public function testCanMockClassWithConstructor()
    {
        $m = mock('MockeryTest_ClassConstructor');
        $this->assertInstanceOf(MockeryTest_ClassConstructor::class, $m);
    }

    public function testCanMockClassWithConstructorNeedingClassArgs()
    {
        $m = mock('MockeryTest_ClassConstructor2');
        $this->assertInstanceOf(MockeryTest_ClassConstructor2::class, $m);
    }

    /**
     * @group partial
     */
    public function testCanPartiallyMockANormalClass()
    {
        $m = mock('MockeryTest_PartialNormalClass[foo]');
        $this->assertInstanceOf(MockeryTest_PartialNormalClass::class, $m);
        $m->shouldReceive('foo')->andReturn('cba');
        $this->assertEquals('abc', $m->bar());
        $this->assertEquals('cba', $m->foo());
    }

    /**
     * @group partial
     */
    public function testCanPartiallyMockAnAbstractClass()
    {
        $m = mock('MockeryTest_PartialAbstractClass[foo]');
        $this->assertInstanceOf(MockeryTest_PartialAbstractClass::class, $m);
        $m->shouldReceive('foo')->andReturn('cba');
        $this->assertEquals('abc', $m->bar());
        $this->assertEquals('cba', $m->foo());
    }

    /**
     * @group partial
     */
    public function testCanPartiallyMockANormalClassWith2Methods()
    {
        $m = mock('MockeryTest_PartialNormalClass2[foo, baz]');
        $this->assertInstanceOf(MockeryTest_PartialNormalClass2::class, $m);
        $m->shouldReceive('foo')->andReturn('cba');
        $m->shouldReceive('baz')->andReturn('cba');
        $this->assertEquals('abc', $m->bar());
        $this->assertEquals('cba', $m->foo());
        $this->assertEquals('cba', $m->baz());
    }

    /**
     * @group partial
     */
    public function testCanPartiallyMockAnAbstractClassWith2Methods()
    {
        $m = mock('MockeryTest_PartialAbstractClass2[foo,baz]');
        $this->assertInstanceOf(MockeryTest_PartialAbstractClass2::class, $m);
        $m->shouldReceive('foo')->andReturn('cba');
        $m->shouldReceive('baz')->andReturn('cba');
        $this->assertEquals('abc', $m->bar());
        $this->assertEquals('cba', $m->foo());
        $this->assertEquals('cba', $m->baz());
    }

    /**
     * @group partial
     */
    public function testThrowsExceptionIfSettingExpectationForNonMockedMethodOfPartialMock()
    {
        $this->markTestSkipped('For now...');
        $m = mock('MockeryTest_PartialNormalClass[foo]');
        $this->assertInstanceOf(MockeryTest_PartialNormalClass::class, $m);
        $this->expectException(\Mockery\Exception::class);
        $m->shouldReceive('bar')->andReturn('cba');
    }

    /**
     * @group partial
     */
    public function testThrowsExceptionIfClassOrInterfaceForPartialMockDoesNotExist()
    {
        $this->expectException(\Mockery\Exception::class);
        $m = mock('MockeryTest_PartialNormalClassXYZ[foo]');
    }

    /**
     * @group issue/4
     */
    public function testCanMockClassContainingMagicCallMethod()
    {
        $m = mock('MockeryTest_Call1');
        $this->assertInstanceOf(MockeryTest_Call1::class, $m);
    }

    /**
     * @group issue/4
     */
    public function testCanMockClassContainingMagicCallMethodWithoutTypeHinting()
    {
        $m = mock('MockeryTest_Call2');
        $this->assertInstanceOf(MockeryTest_Call2::class, $m);
    }

    /**
     * @group issue/14
     */
    public function testCanMockClassContainingAPublicWakeupMethod()
    {
        $m = mock('MockeryTest_Wakeup1');
        $this->assertInstanceOf(MockeryTest_Wakeup1::class, $m);
    }

    /**
     * @group issue/18
     */
    public function testCanMockClassUsingMagicCallMethodsInPlaceOfNormalMethods()
    {
        $m = Mockery::mock('Gateway');
        $m->shouldReceive('iDoSomethingReallyCoolHere');
        $m->iDoSomethingReallyCoolHere();
    }

    /**
     * @group issue/18
     */
    public function testCanPartialMockObjectUsingMagicCallMethodsInPlaceOfNormalMethods()
    {
        $m = Mockery::mock(new Gateway);
        $m->shouldReceive('iDoSomethingReallyCoolHere');
        $m->iDoSomethingReallyCoolHere();
    }

    /**
     * @group issue/13
     */
    public function testCanMockClassWhereMethodHasReferencedParameter()
    {
        $this->assertInstanceOf(MockInterface::class, Mockery::mock(new MockeryTest_MethodParamRef));
    }

    /**
     * @group issue/13
     */
    public function testCanPartiallyMockObjectWhereMethodHasReferencedParameter()
    {
        $this->assertInstanceOf(MockInterface::class, Mockery::mock(new MockeryTest_MethodParamRef2));
    }

    /**
     * @group issue/11
     */
    public function testMockingAKnownConcreteClassCanBeGrantedAnArbitraryClassType()
    {
        $m = mock('alias:MyNamespace\MyClass');
        $m->shouldReceive('foo')->andReturn('bar');
        $this->assertEquals('bar', $m->foo());
        $this->assertInstanceOf(MyNamespace\MyClass::class, $m);
    }

    /**
     * @group issue/15
     */
    public function testCanMockMultipleInterfaces()
    {
        $m = mock('MockeryTest_Interface1, MockeryTest_Interface2');
        $this->assertInstanceOf(MockeryTest_Interface1::class, $m);
        $this->assertInstanceOf(MockeryTest_Interface2::class, $m);
    }

    /**
     */
    public function testCanMockMultipleInterfacesThatMayNotExist()
    {
        $m = mock('NonExistingClass, MockeryTest_Interface1, MockeryTest_Interface2, \Some\Thing\That\Doesnt\Exist');
        $this->assertInstanceOf(MockeryTest_Interface1::class, $m);
        $this->assertInstanceOf(MockeryTest_Interface2::class, $m);
        $this->assertInstanceOf(\Some\Thing\That\Doesnt\Exist::class, $m);
    }

    /**
     * @group issue/15
     */
    public function testCanMockClassAndApplyMultipleInterfaces()
    {
        $m = mock('MockeryTestFoo, MockeryTest_Interface1, MockeryTest_Interface2');
        $this->assertInstanceOf(MockeryTestFoo::class, $m);
        $this->assertInstanceOf(MockeryTest_Interface1::class, $m);
        $this->assertInstanceOf(MockeryTest_Interface2::class, $m);
    }

    /**
     * @group issue/7
     *
     * Noted: We could complicate internally, but a blind class is better built
     * with a real class noted up front (stdClass is a perfect choice it is
     * behaviourless). Fine, it's a muddle - but we need to draw a line somewhere.
     */
    public function testCanMockStaticMethods()
    {
        $m = mock('alias:MyNamespace\MyClass2');
        $m->shouldReceive('staticFoo')->andReturn('bar');
        $this->assertEquals('bar', \MyNameSpace\MyClass2::staticFoo());
    }

    /**
     * @group issue/7
     */
    public function testMockedStaticMethodsObeyMethodCounting()
    {
        $m = mock('alias:MyNamespace\MyClass3');
        $m->shouldReceive('staticFoo')->once()->andReturn('bar');
        $this->expectException(\Mockery\CountValidator\Exception::class);
        Mockery::close();
    }

    /**
     */
    public function testMockedStaticThrowsExceptionWhenMethodDoesNotExist()
    {
        $m = mock('alias:MyNamespace\StaticNoMethod');
        try {
            MyNameSpace\StaticNoMethod::staticFoo();
        } catch (BadMethodCallException $e) {
            // Mockery + PHPUnit has a fail safe for tests swallowing our
            // exceptions
            $e->dismiss();
            return;
        }

        $this->fail('Exception was not thrown');
    }

    /**
     * @group issue/17
     */
    public function testMockingAllowsPublicPropertyStubbingOnRealClass()
    {
        $m = mock('MockeryTestFoo');
        $m->foo = 'bar';
        $this->assertEquals('bar', $m->foo);
        //$this->assertArrayHasKey('foo', $m->mockery_getMockableProperties());
    }

    /**
     * @group issue/17
     */
    public function testMockingAllowsPublicPropertyStubbingOnNamedMock()
    {
        $m = mock('Foo');
        $m->foo = 'bar';
        $this->assertEquals('bar', $m->foo);
        //$this->assertArrayHasKey('foo', $m->mockery_getMockableProperties());
    }

    /**
     * @group issue/17
     */
    public function testMockingAllowsPublicPropertyStubbingOnPartials()
    {
        $m = mock(new stdClass);
        $m->foo = 'bar';
        $this->assertEquals('bar', $m->foo);
        //$this->assertArrayHasKey('foo', $m->mockery_getMockableProperties());
    }

    /**
     * @group issue/17
     */
    public function testMockingDoesNotStubNonStubbedPropertiesOnPartials()
    {
        $m = mock(new MockeryTest_ExistingProperty);
        $this->assertEquals('bar', $m->foo);
        $this->assertArrayNotHasKey('foo', $m->mockery_getMockableProperties());
    }

    public function testCreationOfInstanceMock()
    {
        $m = mock('overload:MyNamespace\MyClass4');
        $this->assertInstanceOf(MyNamespace\MyClass4::class, $m);
    }

    public function testInstantiationOfInstanceMock()
    {
        $m = mock('overload:MyNamespace\MyClass5');
        $instance = new MyNamespace\MyClass5;
        $this->assertInstanceOf(MyNamespace\MyClass5::class, $instance);
    }

    public function testInstantiationOfInstanceMockImportsExpectations()
    {
        $m = mock('overload:MyNamespace\MyClass6');
        $m->shouldReceive('foo')->andReturn('bar');
 