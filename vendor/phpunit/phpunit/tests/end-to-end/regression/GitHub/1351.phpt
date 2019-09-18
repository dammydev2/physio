on::class);

        $this->assertObjectNotHasAttribute('foo', null);
    }

    public function testAssertObjectNotHasAttributeThrowsExceptionIfAttributeNameIsNotValid(): void
    {
        $this->expectException(Exception::class);

        $this->assertObjectNotHasAttribute('1', \ClassWithNonPublicAttributes::class);
    }

    public function testClassHasPublicAttribute(): void
    {
        $this->assertClassHasAttribute('publicAttribute', \ClassWithNonPublicAttributes::class);

        $this->expectException(AssertionFailedError::class);

        $this->assertClassHasAttribute('attribute', \ClassWithNonPublicAttributes::class);
    }

    public function testClassNotHasPublicAttribute(): void
    {
        $this->assertClassNotHasAttribute('attribute', \ClassWithNonPublicAttributes::class);

        $this->expectException(AssertionFailedError::class);

        $this->assertClassNotHasAttribute('publicAttribute', \ClassWithNonPublicAttributes::class);
    }

    public function testClassHasPublicStaticAttribute(): void
    {
        $this->assertClassHasSta