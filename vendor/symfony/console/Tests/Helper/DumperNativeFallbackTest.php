ode "-1" is not valid.
     */
    public function testInvalidModes()
    {
        new InputOption('foo', 'f', '-1');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEmptyNameIsInvalid()
    {
        new InputOption('');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDoubleDashNameIsInvalid()
    {
        new InputOption('--');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSingleDashOptionIsInvalid()
    {
        new InputOption('foo', '-');
    }

    public function testIsArray()
    {
        $option = new InputOption('foo', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY);
        $this->assertTrue($option->isArray(), '->isArray() returns true if the option can be an array');
        $option = new InputOption('foo', null, InputOption::VALUE_NONE);
        $this->assertFalse($option->isArray(), '->isArray() returns false if the option can not be an array');
    }

    public function testGetDescription()
    {
        $option = new InputOption('foo', 'f', null, 'Some description');
        $this->assertEquals('Some description', $option->getDescription(), '->getDescription() returns the description message');
    }

    public function testGetDefault()
    {
        $option = new InputOption('foo', null, InputOption::VALUE_OPTIONAL, '', 'default');
        $this->assertEquals('default', $option->getDefault(), '->getDefault() returns th