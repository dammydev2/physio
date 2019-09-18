ctException(AssertionFailedError::class);

        $this->assertNotContains('foo', ['foo']);
    }

    public function testAssertArrayNotContainsNonObject(): void
    {
        $this->assertNotContains('foo', [true], '', false, true, true);

        $this->expectException(AssertionFailedError::class);

        $this->assertNotContains('foo', [true]);
    }

    public function testAssertStringNotContainsString(): void
    {
        $this->assertNotContains('foo', 'bar');

        $this->expectException(AssertionFailedError::class);

        $this->assertNotContains('foo', 'foo');
    }

    public function testAssertStringNotContainsStringForUtf8(): void
    {
        $this->assertNotContains('ORYGINAŁ', 'oryginał');

        $this->expectException(AssertionFailedError::class);

        $this->assertNotContains('oryginał', 'oryginał');
    }

    public function testAssertStringNotContainsStringForUtf8WhenIgnoreCase(): void
    {
        $this->expectException(AssertionFailedError::class);

        $this->assertNotContains('ORYGINAŁ', 'oryginał', '', true);
 