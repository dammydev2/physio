tFile(TEST_FILES_PATH . 'expectedFileFormat.txt', "BAR\n");

        $this->expectException(AssertionFailedError::class);

        $this->assertStringNotMatchesFormatFile(TEST_FILES_PATH . 'expectedFileFormat.txt', "FOO\n");
    }

    public function testStringsCanBeComparedForEqualityIgnoringCase(): void
    {
        $this->assertEqualsIgnoringCase('a', 'A');

        $this->assertNotEqualsIgnoringCase('a', 'B');
    }

    public function testArraysOfStringsCanBeComparedForEqualityIgnoringCase(): void
    {
        $this->assertEqualsIgnoringCase(['a'], ['A']);

        $this->assertNotEqualsIgnoringCase(['a'], ['B']);
    }

    public function testStringsCanBeComparedForEqualityWithDelta(): void
    {
        $this->assertEqualsWithDelta(2.3, 2.5, 0.5);

        $this->assertNotEqualsWithDelta(2.3, 3.5, 0.5);
    }

    public function testArraysOfStringsCanBeComparedForEqualityWithDelta(): void
    {
        $this->assertEqualsWithDelta([2.3]