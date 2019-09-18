ertiesWhenRequestedMoreTimesThanSetValues()
    {
        $this->mock->bar = null;
        $this->mock->shouldReceive('foo')->andSet('bar', 'baz', 'bazz');
        $this->assertNull($this->mock->bar);
        $this->mock->foo();
        $this->assertEquals('baz', $this->mock->bar);
        $this->mock->foo();
        $this->assertEquals('bazz', $this->mock->bar);
        $this->mock->foo();
        $this->assertEquals('bazz', $this->mock->bar);
    }

    public function testSetsPublicPropertiesWhenRequestedMoreTimesThanSetValuesUsingAlias()
    {
        $this->mock->bar = null;
        $this->mock->shouldReceive('foo')->andSet('bar', 'baz', 'bazz');
        $this->assertNull($this->mock->bar);
        $this->mock->foo();
        $this->assertEquals('baz', $this->mock->bar);
        $this->mock->foo();
        $this->assertEquals('bazz', $this->mock->bar);
        $this->mock->foo();
        $this->assertEquals('bazz', $this->mock->bar);
    }

    public function testSetsPublicPropertiesWhenRequestedMoreTimesThanSetValuesWithDirectSet()
    {
        $this->mock->bar = null;
        $this->mock->shouldReceive('foo')->andSet('bar', 'baz', 'bazz');
        $this->assertNull($this->mock->bar);
        $this->mock->foo();
        $this->assertEquals('baz', $this->mock->bar);
        $this->mock->foo();
        $this->assertEquals('bazz', $this->mock->bar);
        $this->mock->bar = null;
        $this->mock->foo();
        $this->assertNull($this->mock->bar);
    }

    public function testSetsPublicPropertiesWhenRequestedMoreTimesThanSetValuesWithDirectSetUsingAlias()
    {
        $this->mock->bar = null;
        $this