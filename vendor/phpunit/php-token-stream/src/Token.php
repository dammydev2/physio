sertInfinite($actual, string $message = ''): void
    {
        static::assertThat($actual, static::isInfinite(), $message);
    }

    /**
     * Asserts that a variable is nan.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertNan($actual, string $message = ''): void
    {
        static::assertThat($actual, static::isNan(), $message);
    }

    /**
     * Asserts that a class has a specified attribute.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertClassHasAttribute(string $attributeName, string $className, string $message = ''): void
    {
        if (!self::isValidClassAttributeName($attributeName)) {
            throw InvalidArgumentHelper::factory(1, 'valid attribute name');
        }

        if (!\class_exists($className)) {
            throw InvalidArgumentHelper::factory(2, 'class name', $className);
        }

        static::assertThat($className, new ClassHasAttribute($attributeName), $message);
    }

    /**
     * Asserts that a class does not have a specified attribute.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertClassNotHasAttribute(string $attributeName, string $className, string $message = ''): void
    {
        if (!self::isValidClassAttributeName($attributeName)) {
            throw InvalidArgumentHelper::factory(1, 'valid attribute name');
        }

        if (!\class_exists($className)) {
            throw InvalidArgumentHelper::factory(2, 'class name', $className);
        }

        static::assertThat(
            $className,
            new LogicalNot(
                new ClassHasAttribute($attributeName)
            ),
            $message
        );
    }

    /**
     * Asserts that a class has a specified static attribute.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertClassHasStaticAttribute(string $attributeName, string $className, string $message = ''): void
    {
        if (!self::isValidClassAttributeName($attributeName)) {
            throw InvalidArgumentHelper::factory(1, 'valid attribute name');
        }

        if (!\class_exists($className)) {
            throw InvalidArgumentHelper::factory(2, 'class name', $className);
        }

        static::assertThat(
            $className,
            new ClassHasStaticAttribute($attributeName),
            $message
        );
    }

    /**
     * Asserts that a class does not have a specified static attribute.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertClassNotHasStaticAttribute(string $attributeName, string $className, string $message = ''): void
    {
        if (!self::isValidClassAttributeName($attributeName)) {
            throw InvalidArgumentHelper::factory(1, 'valid attribute name');
        }

        if (!\class_exists($className)) {
            throw InvalidArgumentHelper::factory(2, 'class name', $className);
        }

        static::assertThat(
            $className,
            new LogicalNot(
                new ClassHasStaticAttribute($attributeName)
            ),
            $message
        );
    }

    /**
     * Asserts that an object has a specified attribute.
     *
     * @param object $object
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertObjectHasAttribute(string $attributeName, $object, string $message = ''): void
    {
        if (!self::isValidObjectAttributeName($attributeName)) {
            throw InvalidArgumentHelper::factory(1, 'valid attribute name');
        }

        if (!\is_object($object)) {
            throw InvalidArgumentHelper::factory(2, 'object');
        }

        static::assertThat(
            $object,
            new ObjectHasAttribute($attributeName),
            $message
        );
    }

    /**
     * Asserts that an object does not have a specified attribute.
     *
     * @param object $object
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertObjectNotHasAttribute(string $attributeName, $object, string $message = ''): void
    {
        if (!self::isValidObjectAttributeName($attributeName)) {
            throw InvalidArgumentHelper::factory(1, 'valid attribute name');
        }

        if (!\is_object($object)) {
            throw InvalidArgumentHelper::factory(2, 'object');
        }

        static::assertThat(
            $object,
            new LogicalNot(
                new ObjectHasAttribute($attributeName)
            ),
            $message
        );
    }

    /**
     * Asserts that two variables have the same type and value.
     * Used on objects, it asserts that two variables reference
     * the same object.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertSame($expected, $actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new IsIdentical($expected),
            $message
        );
    }

    /**
     * Asserts that a variable and an attribute of an object have the same type
     * and value.
     *
     * @param object|string $actualClassOrObject
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     */
    public static function assertAttributeSame($expected, string $actualAttributeName, $actualClassOrObject, string $message = ''): void
    {
        static::assertSame(
            $expected,
            static::readAttribute($actualClassOrObject, $actualAttributeName),
            $message
        );
    }

    /**
     * Asserts that two variables do not have the same type and value.
     * Used on objects, it asserts that two variables do not reference
     * the same object.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertNotSame($expected, $actual, string $message = ''): void
    {
        if (\is_bool($expected) && \is_bool($actual)) {
            static::assertNotEquals($expected, $actual, $message);
        }

        static::assertThat(
            $actual,
            new LogicalNot(
                new IsIdentical($expected)
            ),
            $message
        );
    }

    /**
     * Asserts that a variable and an attribute of an object do not have the
     * same type and value.
     *
     * @param object|string $actualClassOrObject
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     */
    public static function assertAttributeNotSame($expected, string $actualAttributeName, $actualClassOrObject, string $message = ''): void
    {
        static::assertNotSame(
            $expected,
            static::readAttribute($actualClassOrObject, $actualAttributeName),
            $message
        );
    }

    /**
     * Asserts that a variable is of a given type.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertInstanceOf(string $expected, $actual, string $message = ''): void
    {
        if (!\class_exists($expected) && !\interface_exists($expected)) {
            throw InvalidArgumentHelper::factory(1, 'class or interface name');
        }

        static::assertThat(
            $actual,
            new IsInstanceOf($expected),
            $message
        );
    }

    /**
     * Asserts that an attribute is of a given type.
     *
     * @param object|string $classOrObject
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     */
    public static function assertAttributeInstanceOf(string $expected, string $attributeName, $classOrObject, string $message = ''): void
    {
        static::assertInstanceOf(
            $expected,
            static::readAttribute($classOrObject, $attributeName),
            $message
        );
    }

    /**
     * Asserts that a variable is not of a given type.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertNotInstanceOf(string $expected, $actual, string $message = ''): void
    {
        if (!\class_exists($expected) && !\interface_exists($expected)) {
            throw InvalidArgumentHelper::factory(1, 'class or interface name');
        }

        static::assertThat(
            $actual,
            new LogicalNot(
                new IsInstanceOf($expected)
            ),
            $message
        );
    }

    /**
     * Asserts that an attribute is of a given type.
     *
     * @param object|string $classOrObject
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     */
    public static function assertAttributeNotInstanceOf(string $expected, string $attributeName, $classOrObject, string $message = ''): void
    {
        static::assertNotInstanceOf(
            $expected,
            static::readAttribute($classOrObject, $attributeName),
            $message
        );
    }

    /**
     * Asserts that a variable is of a given type.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3369
     */
    public static function assertInternalType(string $expected, $actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new IsType($expected),
            $message
        );
    }

    /**
     * Asserts that an attribute is of a given type.
     *
     * @param object|string $classOrObject
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     */
    public static function assertAttributeInternalType(string $expected, string $attributeName, $classOrObject, string $message = ''): void
    {
        static::assertInternalType(
            $expected,
            static::readAttribute($classOrObject, $attributeName),
            $message
        );
    }

    /**
     * Asserts that a variable is of type array.
     */
    public static function assertIsArray($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new IsType(IsType::TYPE_ARRAY),
            $message
        );
    }

    /**
     * Asserts that a variable is of type bool.
     */
    public static function assertIsBool($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new IsType(IsType::TYPE_BOOL),
            $message
        );
    }

    /**
     * Asserts that a variable is of type float.
     */
    public static function assertIsFloat($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new IsType(IsType::TYPE_FLOAT),
            $message
        );
    }

    /**
     * Asserts that a variable is of type int.
     */
    public static function assertIsInt($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new IsType(IsType::TYPE_INT),
            $message
        );
    }

    /**
     * Asserts that a variable is of type numeric.
     */
    public static function assertIsNumeric($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new IsType(IsType::TYPE_NUMERIC),
            $message
        );
    }

    /**
     * Asserts that a variable is of type object.
     */
    public static function assertIsObject($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new IsType(IsType::TYPE_OBJECT),
            $message
        );
    }

    /**
     * Asserts that a variable is of type resource.
     */
    public static function assertIsResource($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new IsType(IsType::TYPE_RESOURCE),
            $message
        );
    }

    /**
     * Asserts that a variable is of type string.
     */
    public static function assertIsString($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new IsType(IsType::TYPE_STRING),
            $message
        );
    }

    /**
     * Asserts that a variable is of type scalar.
     */
    public static function assertIsScalar($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new IsType(IsType::TYPE_SCALAR),
            $message
        );
    }

    /**
     * Asserts that a variable is of type callable.
     */
    public static function assertIsCallable($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new IsType(IsType::TYPE_CALLABLE),
            $message
        );
    }

    /**
     * Asserts that a variable is of type iterable.
     */
    public static function assertIsIterable($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new IsType(IsType::TYPE_ITERABLE),
            $message
        );
    }

    /**
     * Asserts that a variable is not of a given type.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3369
     */
    public static function assertNotInternalType(string $expected, $actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new LogicalNot(
                new IsType($expected)
            ),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type array.
     */
    public static function assertIsNotArray($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_ARRAY)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type bool.
     */
    public static function assertIsNotBool($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_BOOL)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type float.
     */
    public static function assertIsNotFloat($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_FLOAT)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type int.
     */
    public static function assertIsNotInt($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_INT)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type numeric.
     */
    public static function assertIsNotNumeric($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_NUMERIC)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type object.
     */
    public static function assertIsNotObject($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_OBJECT)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type resource.
     */
    public static function assertIsNotResource($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_RESOURCE)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type string.
     */
    public static function assertIsNotString($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_STRING)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type scalar.
     */
    public static function assertIsNotScalar($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_SCALAR)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type callable.
     */
    public static function assertIsNotCallable($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_CALLABLE)),
            $message
        );
    }

    /**
     * Asserts that a variable is not of type iterable.
     */
    public static function assertIsNotIterable($actual, string $message = ''): void
    {
        static::assertThat(
            $actual,
            new LogicalNot(new IsType(IsType::TYPE_ITERABLE)),
            $message
        );
    }

    /**
     * Asserts that an attribute is of a given type.
     *
     * @param object|string $classOrObject
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     */
    public static function assertAttributeNotInternalType(string $expected, string $attributeName, $classOrObject, string $message = ''): void
    {
        static::assertNotInternalType(
            $expected,
            static::readAttribute($classOrObject, $attributeName),
            $message
        );
    }

    /**
     * Asserts that a string matches a given regular expression.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertRegExp(string $pattern, string $string, string $message = ''): void
    {
        static::assertThat($string, new RegularExpression($pattern), $message);
    }

    /**
     * Asserts that a string does not match a given regular expression.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertNotRegExp(string $pattern, string $string, string $message = ''): void
    {
        static::assertThat(
            $string,
            new LogicalNot(
                new RegularExpression($pattern)
            ),
            $message
        );
    }

    /**
     * Assert that the size of two arrays (or `Countable` or `Traversable` objects)
     * is the same.
     *
     * @param Countable|iterable $expected
     * @param Countable|iterable $actual
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertSameSize($expected, $actual, string $message = ''): void
    {
        if (!$expected instanceof Countable && !\is_iterable($expected)) {
            throw InvalidArgumentHelper::factory(1, 'countable or iterable');
        }

        if (!$actual instanceof Countable && !\is_iterable($actual)) {
            throw InvalidArgumentHelper::factory(2, 'countable or iterable');
        }

        static::assertThat(
            $actual,
            new SameSize($expected),
            $message
        );
    }

    /**
     * Assert that the size of two arrays (or `Countable` or `Traversable` objects)
     * is not the same.
     *
     * @param Countable|iterable $expected
     * @param Countable|iterable $actual
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertNotSameSize($expected, $actual, string $message = ''): void
    {
        if (!$expected instanceof Countable && !\is_iterable($expected)) {
            throw InvalidArgumentHelper::factory(1, 'countable or iterable');
        }

        if (!$actual instanceof Countable && !\is_iterable($actual)) {
            throw InvalidArgumentHelper::factory(2, 'countable or iterable');
        }

        static::assertThat(
            $actual,
            new LogicalNot(
                new SameSize($expected)
            ),
            $message
        );
    }

    /**
     * Asserts that a string matches a given format string.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertStringMatchesFormat(string $format, string $string, string $message = ''): void
    {
        static::assertThat($string, new StringMatchesFormatDescription($format), $message);
    }

    /**
     * Asserts that a string does not match a given format string.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertStringNotMatchesFormat(string $format, string $string, string $message = ''): void
    {
        static::assertThat(
            $string,
            new LogicalNot(
                new StringMatchesFormatDescription($format)
            ),
            $message
        );
    }

    /**
     * Asserts that a string matches a given format file.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertStringMatchesFormatFile(string $formatFile, string $string, string $message = ''): void
    {
        static::assertFileExists($formatFile, $message);

        static::assertThat(
            $string,
            new StringMatchesFormatDescription(
                \file_get_contents($formatFile)
            ),
            $message
        );
    }

    /**
     * Asserts that a string does not match a given format string.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertStringNotMatchesFormatFile(string $formatFile, string $string, string $message = ''): void
    {
        static::assertFileExists($formatFile, $message);

        static::assertThat(
            $string,
            new LogicalNot(
                new StringMatchesFormatDescription(
                    \file_get_contents($formatFile)
                )
            ),
            $message
        );
    }

    /**
     * Asserts that a string starts with a given prefix.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertStringStartsWith(string $prefix, string $string, string $message = ''): void
    {
        static::assertThat($string, new StringStartsWith($prefix), $message);
    }

    /**
     * Asserts that a string starts not with a given prefix.
     *
     * @param string $prefix
     * @param string $string
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertStringStartsNotWith($prefix, $string, string $message = ''): void
    {
        static::assertThat(
            $string,
            new LogicalNot(
                new StringStart