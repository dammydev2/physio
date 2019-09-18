>shouldReceive('foo')->andSet('bar', 'baz');
        $instance = new MyNamespace\MyClass13;
        $instance->foo();
        $this->assertEquals('baz', $m->bar);
        $this->assertEquals('baz', $instance->bar);
    }

    public function testInstantiationOfInstanceMockWithConstructorParameterValidation()
    {
        $m = mock('overload:MyNamespace\MyClass14');
        $params = [
            'value1' => uniqid('test_')
        ];
        $m->shouldReceive('__construct')->with($params);

        new MyNamespace\MyClass14($params);
    }

    public function testInstantiationOfInstanceMockWithConstructorParameterValidationNegative()
    {
        $m = mock('overload:MyNamespace\MyClass15');
        $params = [
            'value1' => uniqid('test_')
        ];
        $m->shouldReceive('__construct')->with($params);

        $this->expectException(\Mockery\Exception\NoMatchingExpectationException::class);
        new MyNamespace\MyClass15([]);
    }

    public function testInstantiationOfInstanceMockWithConstructorParameterValidationException()
    {
        $m = mock('overload:MyNamespace\MyClass16');
        $m->shouldReceive('__construct')
            ->andThrow(new \Exception('instanceMock '.rand(100, 999)));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageRegExp('/^instanceMock \d{3}$/');
        new MyNamespace\MyClass16();
    }

    public function testMethodParamsPassedByReferenceHaveReferencePreserved()
    {
        $m = mock('MockeryTestRef1');
        $m->shouldReceive('foo')->with(
            Mockery::on(function (&$a) {
                $a += 1;
                return true;
            }),
            Mockery::any()
        );
        $a = 1;
        $b = 1;
        $m->foo($a, $b);
        $this->assertEquals(2, $a);
        $this->assertEquals(1, $b);
    }

    public function testMethodParamsPassedByReferenceThroughWithArgsHaveReferencePreserved()
    {
        $m = mock('MockeryTestRef1');
        $m->shouldReceive('foo')->withArgs(function (&$a, $b) {
            $a += 1;
            $b += 1;
            return true;
        });
        $a = 1;
        $b = 1;
        $m->foo($a, $b);
        $this->assertEquals(2, $a);
        $this->assertEquals(1, $b);
    }

    /**
     * Meant to test the same logic as
     * testCanOverrideExpectedParametersOfExtensionPHPClassesToPreserveRefs,
     * but:
     * - doesn't require an extension
     * - isn't actually known to be used
     */
    public function testCanOverrideExpectedParametersOfInternalPHPClassesToPreserveRefs()
    {
        Mockery::getConfiguration()->setInternalClassMethodParamMap(
            'DateTime', 'modify', array('&$string')
        );
        // @ used to avoid E_STRICT for incompatible signature
        @$m = mock('DateTime');
        $this->assertInstanceOf("Mockery\MockInterface", $m, "Mocking failed, remove @ error suppresion to debug");
        $m->shouldReceive('modify')->with(
            Mockery::on(function (&$string) {
                $string = 'foo';
                return true;
            })
        );
        $data ='bar';
        $m->modify($data);
        $this->assertEquals('foo', $data);
        Mockery::getConfiguration()->resetInternalClassMethodParamMaps();
    }

    /**
     * Real world version of
     * testCanOverrideExpectedParametersOfInternalPHPClassesToPreserveRefs
     */
    public function testCanOverrideExpectedParametersOfExtensionPHPClassesToPreserveRefs()
    {
        if (!class_exists('MongoCollection', false)) {
            $this->markTestSkipped('ext/mongo not installed');
        }
        Mockery::getConfiguration()->setInternalClassMethodParamMap(
            'MongoCollection', 'insert', array('&$data', '$options')
        );
        // @ used to avoid E_STRICT for incompatible signature
        @$m = mock('MongoCollection');
        $this->assertInstanceOf("Mockery\MockInterface", $m, "Mocking failed, remove @ error suppresion to debug");
        $m->shouldReceive('insert')->with(
            Mockery::on(function (&$data) {
                $data['_id'] = 123;
                return true;
            }),
            Mockery::type('array')
        );
        $data = array('a'=>1,'b'=>2);
        $m->insert($data, array());
        $this->assertArrayHasKey('_id', $data);
        $this->assertEquals(123, $data['_id']);
        Mockery::getConfiguration()->resetInternalClassMethodParamMaps();
    }

    public function testCanCreateNonOverridenInstanceOfPreviouslyOverridenInternalClasses()
    {
        Mockery::getConfiguration()->setInternalClassMethodParamMap(
            'DateTime', 'modify', array('&$string')
        );
        // @ used to avoid E_STRICT for incompatible signature
        @$m = mock('DateTime');
        $this->assertInstanceOf("Mockery\MockInterface", $m, "Mocking failed, remove @ error suppresion to debug");
        $rc = new ReflectionClass($m);
        $rm = $rc->getMethod('modify');
        $params = $rm->getParameters();
        $this->assertTrue($params[0]->isPassedByReference());

        Mockery::getConfiguration()->resetInternalClassMethodParamMaps();

        $m = mock('DateTime');
        $this->assertInstanceOf("Mockery\MockInterface", $m, "Mocking failed");
        $rc = new ReflectionClass($m);
        $rm = $rc->getMethod('modify');
        $params = $rm->getParameters();
        $this->