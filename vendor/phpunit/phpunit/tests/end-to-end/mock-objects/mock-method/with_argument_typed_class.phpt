::class);

        $this->assertArrayNotHasKey('foo', ['foo' => 'bar']);
    }

    public function testAssertArrayHasKeyAcceptsArrayObjectValue(): void
    {
        $array        = new \ArrayObject;
        $array['foo'] = 'bar';

        $this->assertArrayHasKey('foo', $array);
    }

    public function testAssertArrayHasKeyProperlyFailsWithArrayObjectValue(): void
    {
        $array        = new \ArrayObject;
        $array['bar'] = 'bar';

        $this->expectException(AssertionFailedError::class);

        $this->assertArrayHasKey('foo', $array);
    }

    public function testAssertArrayHasKeyAcceptsArrayAccessValue(): void
    {
        $array        = new \SampleArrayAccess;
        $array['foo'] = 'bar';

        $this->assertArrayHasKey('foo', $array);
    }

    public function testAssertArrayHasKeyProperlyFailsWithArrayAccessValue(): void
    {
        $array        = new \SampleArrayAccess;
        $array['bar'] = 'bar';

        $this->expectException(AssertionFailedError::class);

        $this->assertArrayHasKey('foo', $array);
    }

    public functi