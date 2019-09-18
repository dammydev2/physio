ockery\Exception::class);
        $this->mock->foo(1);
        Mockery::close();
    }

    public function testIntConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(Mockery::type('int'))->once();
        $this->mock->foo(2);
    }

    public function testIntConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, Mockery::type('int'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
    }

    public function testIntConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(Mockery::type('int'));
        $this->expectException(\Mockery\Exception::class);
        $this->mock->foo('f');
        Mockery::close();
    }

    public function t