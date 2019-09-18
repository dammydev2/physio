blic function testRealConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, Mockery::type('real'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
    }

    public function testRealConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(Mockery::type('real'));
        $this->expectException(\Mockery\Exception::class);
        $this->mock->foo('f');
        Mockery::close();
    }

    public function testResourceConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(Mockery::type('resource'))->once();
        $r = fopen(dirname(__FILE__) . '/_files/file.txt', 'r');
        $this->mock->foo($r);
    }

    public function testResourceConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, Mockery::type('resource'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
    }

    public function testResourceConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(Mockery::type('resource'));
        $this->expectException(\Mockery\Exception::class);
        $this->mock->foo('f');
        Mockery::close();
    }

    public function testScalarConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(Mockery::type('scalar'))->once();
        $this->mock->foo(2);
    }

    public function testScalarCons