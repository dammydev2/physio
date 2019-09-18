is->assertGreaterThan(2, 1);
    }

    public function testAttributeGreaterThan(): void
    {
        $this->assertAttributeGreaterThan(
            1,
            'bar',
            new \ClassWithNonPublicAttributes
        );

        $this->expectException(AssertionFailedError::class);

        $this->assertAttributeGreaterThan(
            1,
            'foo',
            new \ClassWithNonPublicAttributes
        );
    }

    public function testGreaterThanOrEqual(): void
    {
        $this->assertGreaterThanOrEqual(1, 2);

        $this->expectException(AssertionFailedError::class);

        $this->assertGreaterThanOrEqual(2, 1);
    }

    public function testAttributeGreaterThanOrEqual(): void
    {
        $this->assertAttributeGreaterThanOrEqual(
            1,
            'bar',
            new \ClassWithNonPublicAttributes
        );

        $this->expectException(AssertionFailedError::class);

        $this->assertAttributeGreaterThanOrEqual(
            2,
            'foo',
            new \ClassWithNonPublicAttributes
        );
    }

