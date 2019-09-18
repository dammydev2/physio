ck->shouldReceive('foo')->atMost()->once();
        $this->mock->foo();
    }

    public function testCalledAtMostAtExactlyThreeCalls()
    {
        $this->mock->shouldReceive('foo')->atMost()->times(3);
        $this->mock->foo();
        $this->mock->foo();
        $this->mock->foo();
    }

    public function testCalledAtLeastThrowsExceptionOnTooManyCalls()
    {
        $this->mock->shouldReceive('foo')->atMost()->twice();
        $this->mock->foo();
        $this->mock->foo();
        $this->expectException(\Mockery\CountValidator\Exception::class);
        $this->mock->foo();
        Mockery::close();
    }

    public function testExactCountersOverrideAnyPriorSetNonExactCounters()
    {
        $this->mock->shouldReceive('foo')->atLeast()->once()->once();
        $this->mock->foo();
        $this->expectException(\Mockery\CountValidator\Exception::class);
        $this->mock->foo();
        Mockery::close();
    }

    public function testComboOfLeastAndMostCallsWithOneCall()
    {
        $this->mock->shouldReceive('foo')->atleast()->once()->atMost()->twice();
        $this->mock->foo();
    }

    public function testComboOfLeastAndMostCallsWithTwoCalls()
    {
        $this->mock->shouldReceive('foo')->atleast()->once()->atMost()->twice();
        $this->mock->foo();
        $this->mock->foo();
    }

    public function testComboOfLeastAndMostCallsThrowsExceptionAtTooFewCalls()
    {
        $this->mock->shouldReceive('foo')->atleas