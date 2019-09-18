s->mock->foo(4);
    }

    public function testOnConstraintMatchesArgumentOfTypeArray_ClosureEvaluatesToTrue()
    {
        $function = function ($arg) {
            return is_array($arg);
        };
        $this->mock->shouldReceive('foo')->with(Mockery::on($function))->once();
        $this->mock->foo([4, 5]);
    }

    public function testOnConstraintThrowsExceptionWhenConstraintUnmatched_ClosureEvaluatesToFalse()
    {
        $function = function ($arg) {
            return $arg % 2 == 0;
        };
        $this->mock->shouldReceive('foo')->with(Mockery::on($function));
        $this->expectException(\Mockery\Exception::class);
        $this->mock->foo(5);
        Mockery::close();
    }

    public function testMustBeConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(Mockery::mustBe(2))->once();
        $this->mock->foo(2);
    }

    public function testMustBeConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, Mockery::mustBe(2))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
    }

    public function testMustBeConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(Mockery::mustBe(2));
        $this->expectException(\Mockery\Exception::class);
        $this->mock->foo('2');
        Mockery::close();
    }

    public function testMustBeConstraintMatchesObjectArgumentWithEqualsComparisonNotIdentical()
    {
        $a = new stdClass;
        $a->foo = 1;
        $b = new stdClass;
        $b->foo = 1;
        $this->mock->shouldReceive('foo')->with(Mockery::mustBe($a))->once();
        $this->mock->foo($b);
    }

    public function te