     $object         = new \stdClass;
        $object->{'東京'} = 2020;

        $this->assertObjectNotHasAttribute('長野', $object);

        $this->expectException(AssertionFailedError::class);

        $this->assertObjectNotHasAttribute('東京', $object);
    }

    public function testAssertFinite(): void
    {
        $this->assertFinite(1);

        $this->expectException(AssertionFailedError::class);

        $this->assertFinite(\INF);
    }

    public function testAssertInfinite(): void
    {
        $this->assertInfinite(\INF);

        $this->expectException(AssertionFailedError::class);

        $this->assertInfinite(1);
    }

    public function testAssertNan(): void
    {
        $this->assertNan(\NAN);

        $this->expectException(AssertionFailedError::class);

        $this->assertNan(1);
    }

    public function testAssertNull(): void
    {
        $this->assertNull(null);

        $this->expectException(AssertionFailedError::class);

        $this->assertNull(new \stdClass);
    }

    public function testAssertNotNull(): void
    {
        