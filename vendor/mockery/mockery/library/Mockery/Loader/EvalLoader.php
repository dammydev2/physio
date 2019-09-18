    {
        $this->mock->shouldReceive('foo')->once();
        $this->mock->shouldReceive('foo')->with(Mockery::pattern('/foo.*/'))->never();
        $this->mock->foo('bar');
    }

    public function testPatternConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(Mockery::pattern('/foo.*/'));
        $this->expectException(\Mockery\Exception::class);
        $this->mock->foo('bar');
        Mockery::close();
    }

    public function testGlobalConfigMayForbidMockingNonExistentMethodsOnClasses()
    {
        \Mockery::getConfiguration()->allowMockingNonExistentMethods(false);
        $mock = mock('stdClass');
        $this->expectException(\Mockery\Exception::class);
        $mock->shouldReceive('foo');
        Mockery::close();
    }

    public function testGlobalConfigMayForbidMockingNonExistentMethodsOnAutoDeclaredClasses()
    {
        \Mockery::getConfiguration()->allowMockingNonExistentMethods(false);
        $this->expectException(\Mockery\