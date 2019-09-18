    $mock = mock('ClassWithMethods')->shouldIgnoreMissing();
        $this->expectException(\BadMethodCallException::class);
        $mock->nonExistentMethod();
    }

    public function testShouldIgnoreMissingCallingExistentMethods()
    {
        Mockery::getConfiguration()->allowMockingNonExistentMethods(false);
        $mock = mock('ClassWithMethods')->shouldIgnoreMissing();
        assertThat(nullValue($mock->foo()));
        $mock->shouldReceive('bar')->passthru();
        assertThat($mock->bar(), equalTo('bar'));
    }

    public function testShouldIgnoreMissingCallingNonExistentMethods()
    {
        Mockery::getConfiguration()->allowMockingNonExistentMethods(true);
        $mock = mock('ClassWithMethods')->shouldIgnoreMissing();
        assertThat(nullValue($mock->foo()));
        assertThat(nullValue($mock->bar()));
        assertThat(nullValue($mock->nonExistentMethod()));

        $mock->shouldReceive(array('foo' => 'new_foo', 'nonExistentMethod' => 'result'));
        $mock->shouldReceive('bar')->passthru();
        assertThat($mock->foo(), equalTo('new_foo'));
        assertThat($mock->bar(), equalTo('bar'));
        assertThat($mock->nonExistentMethod(), equalTo('result'));
    }

    public function testCanMockException()
    {
        $exception = Mockery::mock('Exception');
        $this->assertInstanceOf('Exception', $exception);
    }

    public function testCanMockSubclassOfException()
    {
        $errorException = Mockery::mock('ErrorException');
        $this->assertInstanceOf('ErrorException', $errorException);
        $this->assertInstanceOf('Exception', $errorException);
    }

    public function testCallingShouldReceiveWithoutAValidMethodName()
    {
        $mock = Mockery::mock();

        $this->expectException("InvalidArgumentException", "Received empty method name");
        $mock->shouldReceive("");
    }

    public function testShouldThrowExceptionWithInvalidClassName()
    {
        $this->expectException(\Mockery\Exception::class);
        mock('ClassName.CannotContainDot');
    }


    /** @test */
    public function expectation_count_will_count_expectations()
    {
        $mock = new Mock();
        $mock->shouldReceive("doThis")->once();
        $mock->shouldReceive("doThat")->once();

        $this->assertEquals(2, $mock->mockery_getExpectationCou