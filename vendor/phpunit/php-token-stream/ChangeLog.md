      new IsEqual(
                $expected,
                0.0,
                10,
                true,
                false
            )
        );

        static::assertThat($actual, $constraint, $message);
    }

    /**
     * Asserts that two variables are not equal (ignoring case).
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertNotEqualsIgnoringCase($expected, $actual, string $message = ''): void
    {
        $constraint = new LogicalNot(
            new IsEqual(
                $expected,
                0.0,
                10,
                false,
                true
            )
        );

        static::assertThat($actual, $constraint, $message);
    }

    /**
     * Asserts that two variables are not equal (with delta).
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertNotEqualsWithDelta($expected, $actual, float $delta, string $message = ''): void
    {
        $constraint = new LogicalNot(
            new IsEqual(
                $expected,
                $delta
            )
        );

        static::assertThat($actual, $constraint, $message);
    }

    /**
     * Asserts that a variable is not equal to an attribute of an object.
     *
     * @param object|stri