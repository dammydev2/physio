ObjectIdentity,
                    $checkForNonObjectIdentity
                )
            );
        } elseif (\is_string($haystack)) {
            if (!\is_string($needle)) {
                throw InvalidArgumentHelper::factory(
                    1,
                    'string'
                );
            }

            $constraint = new LogicalNot(
                new StringContains(
                    $needle,
                    $ignoreCase
                )
            );
        } else {
            throw InvalidArgumentHelper::factory(
                2,
                'array, traversable or string'
            );
        }

        static::assertThat($haystack, $constraint, $message);
    }

    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object does not contain a needle.
     *
     * @param object|string $haystackClassOrObject
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     */
    public static function assertAttributeNotContains($needle, string $haystackAttributeName, $haystackClassOrObject, string $message = '', bool $ignoreCase = false, bool $checkForObjectIdentity = true, bool $checkForNonObjectIdentity = false): void
    {
        static::assertNotContains(
            $needle,
            static::readAttribute($haystackClassOrObject, $haystackAttributeName),
            $message,
            $ignoreCase,
            $checkForObjectIdentity,
            $checkForNonObjectIdentity
        );
    }

    /**
     * Asserts that a haystack contains only values of a given type.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertContainsOnly(string $type, iterable $haystack, ?bool $isNativeType = null, string $message = ''): void
    {
        if ($isNativeType === null) {
            $isNativeType = Type::isType($type);
        }

        static::assertThat(
            $haystack,
            new TraversableContainsOnly(
                $type,
                $isNativeType
            ),
            $message
        );
    }

    /**
     * Asserts that a haystack contains only instances of a given class name.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidAr