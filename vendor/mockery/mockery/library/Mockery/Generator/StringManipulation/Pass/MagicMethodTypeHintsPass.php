k->shouldReceive('foo')->with(2)->ordered();
        $this->mock->foo(1);
        $this->mock->foo(2);
    }

    public function testDifferentArgumentsAndOrderingsThrowExceptionWhenInWrongOrder()
    {
        $this->mock->shouldReceive('foo')->with(1)->ordered();
        $this->mock->shouldReceive('foo')->with(2)->ordered();
        $this->expectException(\Mockery\Exception::class);
        $this->mock->foo(2);
        $this->mock->foo(1);
        Mockery::close();
    }

    public function testUnorderedCallsIgnoredForOrdering()
    {
        $this->mock->shouldReceive('foo')->with(1)->ordered();
        $this->mock->shouldReceive('foo')->with(2);
        $this->mock->shouldReceive('foo')->with(3)->ordered();
        $this->mock->foo(2);
        $this->mock->foo(1);
        $this->mock->foo(2);
        $this->mock->foo(3);
        $this->mock->foo(2);
    }

    public function testOrderingOfDefaultGrouping()
    {
        $this->mock->shouldReceive('foo')->ordered();
        $this->mock->shouldReceive('bar')->ordered();
        $this->mock->foo();
        $this->mock->bar();
    }

    public function testOrderingOfDefaultGroupingThrowsExceptionOnWrongOrder()
    {
        $this->mock->shouldReceive('foo')->ordered();
        $this->mock->shouldReceive('bar')->ordered();
        $this->expectException(\Mockery\Exception::class);
        $this->mock->bar();
        $this->mock->foo();
        Mockery::close();
    }

    public function testOrderingUsingNumberedGroups()
    {
        $this->mock->shouldReceive('start')->ordered(1);
        $this->mock->shouldReceive('foo')->ordered(2);
        $this->mock->shouldReceive('bar')->ordered(2);
        $this->mock->shouldReceive('final')->ordered();
        $this->mock->start();
        $this->mock->bar();
        $this->mock->foo();
        $this->mock->bar();
        $this->mock->final();
    }

    public function testOrderingUsingNamedGroups()
    {
        $this->mock->shouldReceive('start')->ordered('start');
        $this->mock->shouldReceive('foo')->ordered('foobar');
        $this->mock->shouldReceive('bar')->ordered('foobar');
        $this->mock->shouldReceive('final')->ordered();
        $this->mock->start();
        $this->mock->bar();
        $this->mock->foo();
        $this->mock->bar();
        $this->mock->final();
    }

    /**
     * @group 2A
     */
    public function testGroupedUngroupedOrderingDoNotOverlap()
    {
        $s = $this->mock->shouldReceive('start')->ordered();
        $m = $this->mock->shouldReceive('mid')->ordered('foobar');
        $e = $this->mock->shouldReceive('end')->ordered();
        $this->assertLessThan($m->getOrderNumber(), $s->getOrderNumber());
        $this->assertLessThan($e->getOrderNumber(), $m->getOrderNumber());
    }

    public function testGroupedOrderingThrowsExceptionWhenCallsDisordered()
    {
        $this->mock->shouldReceive('foo')->ordered('first');
        $this->mock->shouldReceive('bar')->ordered('second');
        $this->expectException(\Mockery\Exception::class);
        $this->mock->bar();
        $this->mock->foo();
        Mockery::close();
    }

    public function testExpectationMatchingWithNoArgsOrderings()
    {
        $this->mock->shouldReceive('foo')->withNoArgs()->once()->ordered();
        $this->mock->shouldReceive('bar')->withNoArgs()->once()->ordered();
        $this->mock->shouldReceive('foo')->withNoArgs()->once()->ordered();
        $this->mock->foo();
        $this->mock->bar();
        $this->mock->foo();
    }

    public function testExpectationMatchingWithAnyArgsOrderings()
    {
        $this->mock->shouldReceive('foo')->withAnyArgs()->once()->ordered();
        $this->mock->shouldReceive('bar')->withAnyArgs()->once()->ordered();
        $this->mock->shouldReceive('foo')->withAnyArgs()->once()->ordered();
        $this->mock->foo();
        $this->mock->bar();
        $this->mock->foo();
    }

    public function testEnsuresOrderingIsNotCrossMockByDefault()
    {
        $this->mock->shouldReceive('foo')->ordered();
        $mock2 = mock('bar');
        $mock2->shouldReceive('bar')->ordered();
        $mock2->bar();
        $this->mock->foo();
    }

    public function testEnsuresOrderingIsCrossMockWhenGloballyFlagSet()
    {
        $this->mock->shouldReceive('foo')->globally()->ordered();
        $mock2 = mock('bar');
        $mock2->shouldReceive('bar')->globally()->ordered();
        $this->expectException(\Mockery\Exception::class);
        $mock2->bar();
        $this->mock->foo();
        Mockery::close();
    }

    public function testExpectationCastToStringFormatting()
    {
        $exp = $this->mock->shouldReceive('foo')->with(1, 'bar', new stdClass, array('Spam' => 'Ham', 'Bar' => 'Baz'));
        $this->assertEquals("[foo(1, 'bar', object(stdClass), ['Spam' => 'Ham', 'Bar' => 'Baz'])]", (string) $exp);
    }

    public function testLongExpectationCastToStringFormatting()
    {
        $exp = $this->mock->shouldReceive('foo')->with(array('Spam' => 'Ham', 'Bar' => 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Bar', 'Baz', 'Baz', 'Bar',