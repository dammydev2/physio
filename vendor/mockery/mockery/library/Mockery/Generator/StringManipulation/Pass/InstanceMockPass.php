);
        $this->mock->foo(1);
        Mockery::close();
    }

    public function testExpectsArgumentsArrayThrowsExceptionIfPassedWrongArguments()
    {
        $this->mock->shouldReceive('foo')->withArgs(array(1, 2));
        $this->expectException(\Mockery\Exception::class);
        $this->mock->foo(3, 4);
        Mockery::close();
    }

    public function testExpectsStringArgumentExceptionMessageDifferentiatesBetweenNullAndEmptyString()
    {
        $this->mock->shouldReceive('foo')->withArgs(array('a string'));
        $this->expectException(\Mockery\Exception::class);
        $this->expectExceptionMessageRegExp('/foo\(NULL\)/');
        $this->mock->foo(null);
        Mockery::close();
    }

    public function testExpectsArgumentsArrayThrowsExceptionIfPassedWrongArgumentType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/invalid argument (.+), only array and closure are allowed/');
        $this->mock->shouldReceive('foo')->withArgs(5);
        Mockery::close();
    }

    public function testExpectsArgumentsArrayAcceptAClosureThatValidatesPassedArguments()
    {
        $closure = function ($odd, $even) {
            return ($odd % 2 != 0) && ($even % 2 == 0);
        };
        $this->mock->shouldReceive('foo')->withArgs($closure);
        $this->mock->foo(1, 2);
    }

    public function testExpectsArgumentsArrayThrowsExceptionWhenClosureEvaluatesToFalse()
    {
        $closure = function ($odd, $even) {
            return ($odd % 2 != 0) && ($even % 2 == 0);
        };
        $this->mock->shouldReceive('foo')->withArgs($closure);
        $this->expectException(\Mockery\Exception::class);
        $this->mock->foo(4, 2);
        Mockery::close();
    }

    public function testExpectsArgumentsArrayClosureDoesNotThrowExceptionIfOptionalArgumentsAreMissing()
    {
        $closure = function ($odd, $even, $sum = null) {
            $result = ($odd % 2 != 0) && ($even % 2 == 0);
            if (!is_null($sum)) {
                return $result && ($odd + $even == $sum);
            }
            return $result;
        };
        $this->mock->shouldReceive('foo')->withArgs($closure);
        $this->mock->foo(1, 4);
    }

    public function testExpectsArgumentsArrayClosureDoesNotThrowExceptionIfOptionalArgumentsMathTheExpectation()
    {
        $closure = function ($odd, $even, $sum = null) {
            $result = ($odd % 2 != 0) && ($even % 2 == 0);
            if (!is_null($sum)) {
                return $result && ($odd + $even == $sum);
            }
            return $result;
        };
        $this->mock->shouldReceive('foo')->withArgs($closure);
        $this->mock->foo(1, 4, 5);
    }

    public function testExpectsArgumentsArrayClosureThrowsExceptionIfOptionalArgumentsDontMatchTheExpectation()
    {
        $closure = function ($odd, $even, $sum = null) {
            $result = ($odd % 2 != 0) && ($even % 2 == 0);
            if (!is_null($sum)) {
 