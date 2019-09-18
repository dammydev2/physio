ck->shouldReceive('foo')->with(1, Mockery::ducktype('quack', 'swim'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
    }

    public function testDucktypeConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(Mockery::ducktype('quack', 'swim'));
        $this->expectException(\Mockery\Exception::class);
        $this->mock->foo(new Mockery_Duck_Nonswimmer);
        Mockery::close();
    }

    public function testArrayContentConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(Mockery::subset(array('a'=>1, 'b'=>2)))->once();
        $this->mock->foo(array('a'=>1, 'b'=>2, 'c'=>3));
    }

    public function testArrayContentConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, Mockery::subset(array('a'=>1, 'b'=>2)))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
    }

    public function testArrayContentConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(Mockery::subset(array('a'=>1, 'b'=>2)));
        $this->expectException(\Mockery\Exception::class);
       