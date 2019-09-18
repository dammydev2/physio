    public function testAssertNotEqualsFails($a, $b, $delta = 0.0, $canonicalize = false, $ignoreCase = false): void
    {
        $this->expectException(AssertionFailedError::class);

        $this->assertNotEquals($a, $b, '', $delta, 10, $canonicalize, $ignoreCase);
    }

    /**
     * @dataProvider sameProvider
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAssertSameSucceeds($a, $b): void
    {
        $this->assertSame($a, $b);
    }

    /**
     * @dataProvider notSameProvider
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAssertSameFails($a, $b): void
    {
        $this->expectException(AssertionFailedError::class);

        $this->assertSame($a, $b);
    }

    /**
     * @dataProvider notSameProvider
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
 