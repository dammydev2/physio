assertEquals('bar', $this->mock->foo());
    }

    public function testDefaultExpectationsValidatedInCorrectOrder()
    {
        $this->mock->shouldReceive('foo')->with(1)->once()->andReturn('first')->byDefault();
        $this->mock->shouldReceive('foo')->with(2)->once()->andReturn('second')->byDefault();
        $this->assertEquals('first', $this->mock->foo(1));
        $this->assertEquals('second', $this->mock->foo(2));
    }

    public function testDefaultExpectationsAreReplacedByLaterConcreteExpectations()
    {
        $this->mock->shouldReceive('foo')->andReturn('bar')->once()->byDefault();
        $this->mock->shouldReceive('foo')->andReturn('baz')->twice();
        $this->assertEquals('baz', $this->mock->foo());
        $this->assertEquals('baz', $this->mock->foo());
    }

    public function testExpectationFallsBackToDefaultExpectationWhenConcreteExpectationsAreUsedUp()
    {
        $this->mock->shouldReceive('foo')->with(1)->andReturn('bar')->once()->byDefault();
        $this->mock->shouldReceive('foo')->with(2)->andReturn('baz')->once();
        $this->assertEquals('baz', $this->mock->foo(2));
        $this->assertEquals('bar', $this->mock->foo(1));
    }

    public function testDefaultExpectationsCanBeOrdered()
    {
        $this->mock->shouldReceive('foo')->ordered()->byDefault();
        $this->mock->shouldReceive('bar')->ordered()->byDefault();
        $this->expectException(\Mockery\Exception::class);
        $this->mock->bar();
        $this->mock->foo();
        Mockery::close();
    }

    public function testDefaultExpectationsCanBeOrderedAndReplaced()
    {
        $this->mock->shouldReceive('foo')->ordered()->byDefault();
        $this->mock->shouldReceive('bar')->ordered()->byDefault();
        $this->mock->shouldReceive('bar')->ordered();
        $this->mock->shouldReceive('foo')->ordered();
        $this->mock->bar();
        $this->mock->foo();
    }

    public function testByDefaultOperatesFromMockConstruction()
    {
        $container = new \Mockery\Container(\Mockery::getDefaultGenerator(), \Mockery::getDefaultLoader());
        $mock = $container->mock('f', array('foo'=>'rfoo', 'bar'=>'rbar', 'baz'=>'rbaz'))->byDefault();
        $mock->shouldReceive('foo')->andReturn('foobar');
        $this->assertEquals('foobar', $mock->foo());
        $this->assertEquals('rbar', $mock->bar());
        $this->assertEquals('rbaz', $mock->baz());
    }

    public function testByDefaultOnAMockDoesSquatWithoutExpectations()
    {
        $this->assertInstanceOf(MockInterface::class, mock('f')->byDefault());
    }

    public function testDefaultExpectationsCanBeOverridden()
    {
        $this->mock->shouldReceive('foo')->with('test')->andReturn('bar')->byDefault();
        $this->mock->shouldReceive('foo')->with('test')->andReturn('newbar')->byDefault();
        $this->mock->foo('test');
        $this->assertEquals('newbar', $this->mock->foo('test'));
    }

    public function testByDefaultPreventedFromSettingDefaultWhenDefaultingExpectationWasReplaced()
    {
        $exp = $this->mock->shouldReceive('foo')->andReturn(1);
        $this->mock->shouldReceive('foo')->andReturn(2);
        $this->expectException(\Mockery\Exception::class);
        $exp->byDefault();
        Mockery::close();
    }

    /**
     * Argument Constraint Tests
     */

    public function testAnyConstraintMatchesAnyArg()
    {
        $this->mock->shouldReceive('foo')->with(1, Mockery::any())->twice();
        $this->mock->foo(1, 2);
        $this->mock->foo(1, 'str');
    }

    public function testAnyConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, Mockery::any())->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
    }

    public function testAndAnyOtherConstraintMatchesTheRestOfTheArguments()
    {
        $this->mock->shouldReceive('foo')->with(1, 2, Mockery::andAnyOthers())->twice();
        $this->mock->foo(1, 2, 3, 4, 5);
        $this->mock->foo(1, 'str', 3, 4);
    }

    public function testAndAnyOtherConstraintDoesNotPreventMatchingOfRegularArguments()
    {
        $this->mock->shouldReceive('foo')->with(1, 2, Mockery::andAnyOthers());
        $this->expectException(\Mockery\Exception::class);
        $this->mock->foo(10, 2, 3, 4, 5);
        Mockery::close();
    }

    public function testArrayConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(Mockery::type('array'))->once();
        $this->mock->foo(array());
    }

    public function testArrayConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, Mockery::type('array'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
    }

    public function testArrayConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(Mockery::type('array'));
        $this->expectException(\Mockery\Exception::class);
        $this->mock->foo(1);
        Mockery::close();
    }

    public function testBoolConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(Mockery::type('bool'))->once();
        $this->mock->foo(true);
    }

    public function testBoolConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, Mockery::type('bool'))->never();
        $this->mock->foo