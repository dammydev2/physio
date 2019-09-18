ingEndsNotWith('suffix', 'foo');

        $this->expectException(AssertionFailedError::class);

        $this->assertStringEndsNotWith('suffix', 'foosuffix');
    }

    public function testAssertStringMatchesFormat(): void
    {
        $this->assertStringMatchesFormat('*%s*', '***');
    }

    public function testAssertStringMatchesFormatFailure(): void
    {
        $this->expectException(AssertionFailedError::class);

        $this->assertStringMatchesFormat('*%s*', '**');
    }

    public function testAssertStringNotMatchesFormat(): void
    {
        $this->assertStringNotMatchesFormat('*%s*', '**');

        $this->expectException(AssertionFailedError::class);

        $this->assertStringMatchesFormat('*%s*', '**');
    }

    public function testAssertEmpty(): void
    {
        $this->assertEmpty([]);

        $this->expectException(AssertionFailedError::class);

        $this->assertEmpty(['foo']);
    }

    public function testAssertNotEmpty(): void
    {
        $this->assertNotEmpty(['foo']);

        $this->expectException(AssertionFailedError::class);

        $this->assertNotEmpty([]);
    }

    public function testAssertAttributeEmpty(): void
    {
        $o    = 