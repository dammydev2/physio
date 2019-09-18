rn(null);
        $this->expectException(\TypeError::class);
        $mock->nonNullableClass();
    }

    /**
     * @test
     */
    public function itShouldAllowNullalbeClassToBeSet()
    {
        $mock = mock("test\Mockery\Fixtures\MethodWithNullableReturnType");

        $mock->shouldReceive('nullableClass')->andReturn(new MethodWithNullableReturnType());
        $mock->nullableClass();
    }

    /**
     * @test
     */
    public function itShouldAllowNullableClassToBeNull()
    {
        $mock = mock("test\Mockery\Fixtures\MethodWithNullableReturnType");

        $mock->shouldReceive('nullableClass')->andReturn(null);
        $mock->nullableClass();
    }

    /** @test */
    public function it_allows_returning_null_for_nullable_object_return_types()
    {
        $double= \Mockery::mock(MethodWithNullableReturnType::class);

        $double->shouldReceive("nullableClass")->andReturnNull();

        $this->assertNull($double->nullableClass());
    }

    /** @test */
    public function it_allows_returning_null_for_nullable_string_return_types()
    {
        $double= \Mockery::mock(MethodWithNullableReturnType::class);

        $double->shouldReceive("nullableString")->andReturnNull();

        $this->assertNull($double->nullableString());
    }

    /** @test */
    public function i