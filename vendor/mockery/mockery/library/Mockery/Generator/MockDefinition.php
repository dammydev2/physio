nction testMockeryCloseForIllegalIssetFileInclude()
    {
        $m = Mockery::mock('StdClass')
            ->shouldReceive('get')
            ->andReturn(false)
            ->getMock();
        $m->get();
        Mockery::close();

        // no idea what this test does, adding this as an assertion...
        $this->assertTrue(true);
    }

    public function testMockeryShouldDistinguishBetweenConstructorParamsAndClosures()
    {
        $obj = new MockeryTestFoo();
        $this->assertInstanceOf(MockInterface::class, mock('MockeryTest_ClassMultipleConstructorParams[dave]', [
            &$obj, 'foo'
        ]));
    }

    /** @group nette */
    public function testMockeryShouldNotMockCallstaticMagicMethod()
    {
        $this->assertInstanceOf(MockInterface::class, mock('MockeryTest_CallStatic'));
    }

    /** @group issue/144 */
    public function testMockeryShouldInterpretEmptyArrayAsConstructorArgs()
    {
        $mock = mock("EmptyConstructorTest", array());
        $this->assertSame(0, $mock->numberOfConstructorArgs);
    }

    /** @group issue/144 */
    public function testMockeryShouldCallConstructorByDefaultWhenRequestingPartials()
    {
        $mock = mock("EmptyConstructorTest[foo]");
        $this->assertSame(0, $mock->numberOfConstructorArgs);
  