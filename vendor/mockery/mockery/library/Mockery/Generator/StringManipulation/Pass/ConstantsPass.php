Exception(OutOfBoundsException::class);
        $this->mock->foo();
        Mockery::close();
    }

    /** @test */
    public function and_throws_is_an_alias_to_and_throw()
    {
        $this->mock->shouldReceive('foo')->andThrows(new OutOfBoundsException);

        $this->expectException(OutOfBoundsException::class);
        $this->mock->foo();
    }

    /**
     * @test
     * @requires PHP 7.0.0
     */
    public function it_can_throw_a_throwable()
    {
        $this->expectException(\Error::class);
        $this->mock->shouldReceive('foo')->andThrow(new \Error());
        $this->mock->foo();
    }

    public function testThrowsExceptionBasedOnArgs()
    {
        $this->mock->shouldReceive('foo')->andThrow('OutOfBoundsException');
    