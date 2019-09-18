ributeNotEquals('foo', 'publicStaticAttribute', \ClassWithNonPublicAttributes::class);
    }

    public function testAssertProtectedStaticAttributeEquals(): void
    {
        $this->assertAttributeEquals('bar', 'protectedStaticAttribute', \ClassWithNonPublicAttributes::class);

        $this->expectException(AssertionFailedError::class);

        $this->assertAttributeEquals('foo', 'protectedStaticAttribute', \ClassWithNonPublicAttributes::class);
    }

    public function testAssertProtectedStaticAttributeNotEquals(): void
    {
        $this->assertAttributeNotEquals('foo', 'protectedStaticAttribute', \ClassWithNonPublicAttributes::class);

        $this->expectException(AssertionFailedError::class);

        $this->assertAttributeNotEquals('bar', 'protectedStaticAttribute', \ClassWithNonPublicAttribu