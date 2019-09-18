ectationForbidsFloatNumbers()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->mock->shouldReceive('foo')->times(1.3);
        Mockery::close();
    }

    public function testIfExceptionIndicatesAbsenceOfMethodAndExpectationsOnMock()
    {
        $mock = mock('Mockery_Duck');

        $this->expectException(
            '\BadMethodCallException',
            'Method ' . get_class($mock) .
            '::nonExistent() does not exist on this mock object'
        );

        $mock->nonExistent();
        Mockery::close();
    }

    public function testIfCallingMethodWithNoExpectationsHasSpecificExceptionMessage()
    {
        $mock = mock('Mockery_Duck');

        $this->expectException(
            '\BadMethodCallException',
            'Received ' . get_class($mock) .
            '::quack(), ' . 'but no expectations were specified'
        );

        $mock->quack();
        Mockery::close();
    }

    public function testMockShouldNotBeAnonymousWhenImplementingSpecificInterface()
    {
        $waterMock = mock('IWater');
        $this->assertFalse($waterMock->mockery_isAnonymous());
    }

    public function testWetherMockWithInterfaceOnlyCanNotImplementNonExistingMethods()
    {
        \Mockery::getConfiguration()->allowMoc