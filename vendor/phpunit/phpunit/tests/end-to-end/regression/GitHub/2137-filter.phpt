    }

    public function testAssertSameSizeThrowsExceptionIfExpectedIsNotCountable(): void
    {
        try {
            $this->assertSameSize('a', []);
        } catch (Exception $e) {
            $this->assertEquals('Argument #1 (No Value) of PHPUnit\Framework\Assert::assertSameSize() must be a countable or iterable', $e->getMessage());

            return;
        }

        $this->fail();
    }

    public function testAssertSameSizeThrowsExceptionIfActualIsNotCountable(): void
    {
        try {
            $this->assertSameSize([], '');
        } catch (Exception $e) {
            $this->assertEquals('Argument #2 (No Value) of PHPUnit\Framework\Assert::assertSameSize() must be a countable or ite