: void
    {
        $this->assertThat(new \stdClass, $this->isInstanceOf('StdClass'));
    }

    public function testAssertThatIsType(): void
    {
        $this->assertThat('string', $this->isType('string'));
    }

    public function testAssertThatIsEmpty(): void
    {
        $this->assertThat([], $this->isEmpty());
    }

    public function testAssertThatFileExists(): void
    {
        $this->assertThat(__FILE__, $this->fileExists());
    }

    public function testAssertThatGreaterThan(): void
    {
        $this->assertThat(2, $this->greaterThan(1));
    }

    public function testAssertThatGreaterThanOrEqual(): void
    {
        $this->assertThat(2, $this->greaterThanOrEqual(1));
    }

    public function testAssertThatLessThan(): void
    {
        $this->assertThat(1, $this->lessThan(2));
    }

    public function testAssertThatLessThanOrEqual(): void
    {
        $this->assertThat(1, $this->lessThanOrEqual(2));
    }

    public function testAssertThatMatchesRegularExpression(): void
    {
        $this->assertThat('foobar', $this->matchesRegularExpression('/foo/'));
    }

    publ