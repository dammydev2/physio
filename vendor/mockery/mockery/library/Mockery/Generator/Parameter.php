class);
        $this->expectExceptionMessage('MyTestClass::foo(resource(...))');
        $mock->foo(fopen('php://memory', 'r'));
    }

    public function testHandlesMethodWithArgumentExpectationWhenCalledWithCircularArray()
    {
        $testArray = array();
        $testArray['myself'] =& $testArray;

        $mock = mock('MyTestClass');
        $mock->shouldReceive('foo')->with(array('yourself' => 21));

        $this->expectException(\Mockery\Exception\NoMatchingExpectationException::class);
        $this->expectExceptionMessage("MyTestClass::foo(['myself' => [...]])");
        $mock->foo($testArray);
    }

    public function testHandlesMethodWithArgumentExpectationWhenCalledWithNestedArray()
    {
        $testArray = array();
        $testArray['a_scalar'] = 2;
        $testArray['an_array'] = array(1, 2, 3);

        $mock = mock('MyTestClass');
        $mock->shouldReceive('foo')->with(array('yourself' => 21));

        $this->expectException(\Mockery\Exception\NoMatchingExpectationException::class);
        $this->expectExceptionMessage("MyTestClass::foo(['a_scalar' => 2, 'an_array' => [...]])");
        $mock->foo($testArray);
    }

    public function testHandlesMethodWithArgumentExpectationWhenCalledWithNestedObject()
    {
        $testArray = array();
        $testArray['a_scalar'] = 2;
        $testArray['an_object'] = new stdClass();

        $mock = mock('MyTestClass');
        $mock->shouldReceive('foo')->with(array('yourself' => 21));

        $this->expectException(\Mockery\Exception\NoMatchingExpectationException::class);
        $this->expectExceptionMessage("MyTestClass::foo(['a_scalar' => 2, 'an_object' => object(stdClass)])");
        $mock->foo($testArray);
    }

    public function testHandlesMethodWithArgumentExpectationWhenCalledWithNestedClosure()
    {
        $testArray = array();
        $testArray['a_scalar'] = 2;
        $testArray['a_closure'] = function () {
        };

        $mock = mock('MyTestClass');
        $mock->shouldReceive('foo')->with(array('yourself' => 21));

        $this->expectException(\Mockery\Exception\NoMatchingExpectationException::class);
        $this->expectExceptionMessage("MyTestClass::foo(['a_scalar' => 2, 'a_closure' => object(Closure");
        $mock->foo($testArray);
    }

    public function testHandlesMethodWithArgumentExpectationWhenCalledWithNestedResource()
    {
        $testArray = array();
        $testArray['a_scalar'] = 2;
        $testArray['a_resource'] = fopen('php://memory', 'r');

        $mock = mock('MyTestClass');
        $mock->shouldReceive('foo')->with(array('yourself' => 21));

        $this->expectException(\Mockery\Exception\NoMatchingExpectationException::class);
        $this->expectExceptionMessage("MyTestClass::foo(['a_scalar' => 2, 'a_resource' => resource(...)])");
        $mock->foo($testArray);
    }

    public function testExceptionOutputMakesBooleansLookLikeBooleans()
    {
        $mock = mock('MyTestClass');
        $mock->shouldReceive("foo")->with(123);