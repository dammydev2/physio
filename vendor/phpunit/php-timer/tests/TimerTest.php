
    {
        if ($isNativeType === null) {
            $isNativeType = Type::isType($type);
        }

        static::assertThat(
            $haystack,
            new LogicalNot(
                new TraversableContainsOnly(
                    $type,
                    $isNativeType
                )
            ),
            $message
        );
    }

    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object does not contain only values of a given
     * type.
     *
     * @param object|string $haystackClassOrObject
     * @param bool          $isNativeType
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     */
    public static function assertAttributeNotContainsOnly(string $type, string $haystackAttributeName, $haystackClassOrObject, ?bool $isNativeType = null, string $message = ''): void
    {
        static::assertNotContainsOnly(
            $type,
            static::readAttribute($haystackClassOrObject, $haystackAttributeName),
            $isNativeType,
            $message
        );
    }

    /**
     * Asserts the number of elements of an array, Countable or Traversable.
     *
     * @param Countable|iterable $haystack
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertCount(int $expectedCount, $haystack, string $message = ''): void
    {
        if (!$haystack instanceof Countable && !\is_iterable($haystack)) {
            throw InvalidArgumentHelper::factory(2, 'countable or iterable');
        }

        static::assertThat(
            $haystack,
            new Count($expectedCount),
            $message
        );
    }

    /**
     * Asserts the number of elements of an array, Countable or Traversable
     * that is stored in an attribute.
     *
     * @param object|string $haystackClassOrObject
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     */
    public static function assertAttributeCount(int $expectedCount, string $haystackAttributeName, $haystackClassOrObject, string $message = ''): void
    {
        static::assertCount(
            $expectedCount,
            static::readAttribute($haystackClassOrObject, $haystackAttributeName),
            $message
        );
    }

    /**
     * Asserts the number of elements of an array, Countable or Traversable.
     *
     * @param Countable|iterable $haystack
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertNotCount(int $expectedCount, $haystack, string $message = ''): void
    {
        if (!$haystack instanceof Countable && !\is_iterable($haystack)) {
            throw InvalidArgumentHelper::factory(2, 'countable or iterable');
        }

        $constraint = new LogicalNot(
            new Count($expectedCount)
        );

        static::assertThat($haystack, $constraint, $message);
    }

    /**
     * Asserts the number of elements of an array, Countable or Traversable
     * that is stored in an attribute.
     *
     * @param object|string $haystackClassOrObject
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     */
    public static function assertAttributeNot