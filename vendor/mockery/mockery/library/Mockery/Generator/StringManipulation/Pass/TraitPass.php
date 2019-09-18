oxiesToUndefinedAllowingToString()
    {
        $this->mock->shouldIgnoreMissing()->asUndefined();
        $this->assertTrue(is_string("{$this->mock->g()}"));
        $this->assertTrue(is_string("{$this->mock}"));
    }

    public function testShouldIgnoreMissingDefaultReturnValue()
    {
        $this->mock->shouldIgnoreMissing(1);
        $this->assertEquals(1, $this->mock->a());
    }

    /** @issue #253 */
    public function testShouldIgnoreMissingDefaultSelfAndReturnsSelf()
    {
        $this->mock->shouldIgnoreMissing(\Mockery::self());
        $this->assertSame($this->mock, $this->mock->a()->b());
    }

    public function testToStringMagicMethodCanBeMocked()
    {
        $this->mock->shouldReceive("__toString")->andReturn('dave');
        $this->assertEquals("{$this->mock}", "dave");
    }

    public function testOptionalMockRetrieval()
    {
        $m = mock('f')->shouldReceive('foo')->with(1)->andReturn(3)->mock();
        $this->assertInstanceOf(\Mockery\MockInterface::class, $m);
    }

    public function testNotConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(Mockery::not(1))->once();
        $this->mock->foo(2);
    }

    public function testNotConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
  