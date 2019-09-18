otEqualsXmlFile(string $expectedFile, $actualXml, string $message = ''): void
    {
        $expected = Xml::loadFile($expectedFile);
        $actual   = Xml::load($actualXml);

        static::assertNotEquals($expected, $actual, $message);
    }

    /**
     * Asserts that two XML documents are equal.
     *
     * @param DOMDocument|string $expectedXml
     * @param DOMDocument|string $actualXml
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertXmlStringEqualsXmlString($expectedXml, $actualXml, string $message = ''): void
    {
        $expected = Xml::load($expectedXml);
        $actual   = Xml::load($actualXml);

        static::assertEquals($expected, $actual, $message);
    }

    /**
     * Asserts that two XML documents are not equal.
     *
     * @param DOMDocument|string $expectedXml
     * @param DOMDocument|string $actualXml
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertXmlStringNotEqualsXmlString($expectedXml, $actualXml, string $message = ''): void
    {
        $expected = Xml::load($expectedXml);
        $actual   = Xml::load($actualXml);

        static::assertNotEquals($expected, $actual, $message);
    }

    /**
     * Asserts that a hierarchy of DOMElements matches.
     *
     * @throws AssertionFailedError
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertEqualXMLStructure(DOMElement $expectedElement, DOMElement $actualElement, bool $checkAttributes = false, string $message = ''): void
    {
        $expectedElement = Xml::import($expectedElement);
        $actualElement   = Xml::import($actualElement);

        static::assertSame(
            $expectedElement->tagName,
            $actualElement->tagName,
            $message
        );

        if ($checkAttributes) {
            static::assertSame(
                $expectedElement->attributes->length,
                $actualElement->attributes->length,
                \sprintf(
                    '%s%sNumber of attributes on node "%s" does not match',
                    $message,
                    !empty($message) ? "\n" : '',
                    $expectedElement->tagName
                )
            );

            for ($i = 0; $i < $expectedElement->attributes->length; $i++) {
                /** @var \DOMAttr $expectedAttribute */
                $expectedAttribute = $expectedElement->attributes->item($i);

                /** @var \DOMAttr $actualAttribute */
                $actualAttribute = $actualElement->attributes->getNamedItem(
                    $expectedAttribute->name
                );

                if (!$actualAttribute) {
                    static::fail(
                        \sprintf(
                            '%s%sCould not find attribute "%s" on node "%s"',
                            $message,
                            !empty($message) ? "\n" : '',
                            $expectedAttribute->name,
                            $expectedElement->tagName
                        )
                    );
                }
            }
        }

        Xml::removeCharacterDataNodes($expectedElement);
        Xml::removeCharacterDataNodes($actualElement);

        static::assertSame(
            $expectedElement->childNodes->length,
            $actualElement->childNodes->length,
            \sprintf(
                '%s%sNumber of child nodes of "%s" differs',
                $message,
                !empty($message) ? "\n" : '',
                $expectedElement->tagName
            )
        );

        for ($i = 0; $i < $expectedElement->childNodes->length; $i++) {
            static::assertEqualXMLStructure(
                $expectedElement->childNodes->item($i),
                $actualElement->childNodes->item($i),
                $checkAttributes,
                $message
            );
        }
    }

    /**
     * Evaluates a PHPUnit\Framework\Constraint matcher object.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertThat($value, Constraint $constraint, string $message = ''): void
    {
        self::$count += \count($constraint);

        $constraint->evaluate($value, $message);
    }

    /**
     * Asserts that a string is a valid JSON string.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertJson(string $actualJson, string $message = ''): void
    {
        static::assertThat($actualJson, static::isJson(), $message);
    }

    /**
     * Asserts that two given JSON encoded objects or arrays are equal.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertJsonStringEqualsJsonString(string $expectedJson, string $actualJson, string $message = ''): void
    {
        static::assertJson($expectedJson, $message);
        static::assertJson($actualJson, $message);

        static::assertThat($actualJson, new JsonMatches($expectedJson), $message);
    }

    /**
     * Asserts that two given JSON encoded objects or arrays are not equal.
     *
     * @param string $expectedJson
     * @param string $actualJson
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertJsonStringNotEqualsJsonString($expectedJson, $actualJson, string $message = ''): void
    {
        static::assertJson($expectedJson, $message);
        static::assertJson($actualJson, $message);

        static::assertThat(
            $actualJson,
            new LogicalNot(
                new JsonMatches($expectedJson)
            ),
            $message
        );
    }

    /**
     * Asserts that the generated JSON encoded object and the content of the given file are equal.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertJsonStringEqualsJsonFile(string $expectedFile, string $actualJson, string $message = ''): void
    {
        static::assertFileExists($expectedFile, $message);
        $expectedJson = \file_get_contents($expectedFile);

        static::assertJson($expectedJson, $message);
        static::assertJson($actualJson, $message);

        static::assertThat($actualJson, new JsonMatches($expectedJson), $message);
    }

    /**
     * Asserts that the generated JSON encoded object and the content of the given file are not equal.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertJsonStringNotEqualsJsonFile(string $expectedFile, string $actualJson, string $message = ''): void
    {
        static::assertFileExists($expectedFile, $message);
        $expectedJson = \file_get_contents($expectedFile);

        static::assertJson($expectedJson, $message);
        static::assertJson($actualJson, $message);

        static::assertThat(
            $actualJson,
            new LogicalNot(
                new JsonMatches($expectedJson)
            ),
            $message
        );
    }

    /**
     * Asserts that two JSON files are equal.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertJsonFileEqualsJsonFile(string $expectedFile, string $actualFile, string $message = ''): void
    {
        static::assertFileExists($expectedFile, $message);
        static::assertFileExists($actualFile, $message);

        $actualJson   = \file_get_contents($actualFile);
        $expectedJson = \file_get_contents($expectedFile);

        static::assertJson($expectedJson, $message);
        static::assertJson($actualJson, $message);

        $constraintExpected = new JsonMatches(
            $expectedJson
        );

        $constraintActual = new JsonMatches($actualJson);

        static::assertThat($expectedJson, $constraintActual, $message);
        static::assertThat($actualJson, $constraintExpected, $message);
    }

    /**
     * Asserts that two JSON files are not equal.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public static function assertJsonFileNotEqualsJsonFile(string $expectedFile, string $actualFile, string $message = ''): void
    {
        static::assertFileExists($expectedFile, $message);
        static::assertFileExists($actualFile, $message);

        $actualJson   = \file_get_contents($actualFile);
        $expectedJson = \file_get_contents($expectedFile);

        static::assertJson($expectedJson, $message);
        static::assertJson($actualJson, $message);

        $constraintExpected = new JsonMatches(
            $expectedJson
        );

        $constraintActual = new JsonMatches($actualJson);

        static::assertThat($expectedJson, new LogicalNot($constraintActual), $message);
        static::assertThat($actualJson, new LogicalNot($constraintExpected), $message);
    }

    public static function logicalAnd(): LogicalAnd
    {
        $constraints = \func_get_args();

        $constraint = new LogicalAnd;
        $constraint->setConstraints($constraints);

        return $constraint;
    }

    public static function logicalOr(): LogicalOr
    {
        $constraints = \func_get_args();

        $constraint = new LogicalOr;
        $constraint->setConstraints($constraints);

        return $constraint;
    }

    public static function logicalNot(Constraint $constraint): LogicalNot
    {
        return new LogicalNot($constraint);
    }

    public static function logicalXor(): LogicalXor
    {
        $constraints = \func_get_args();

        $constraint = new LogicalXor;
        $constraint->setConstraints($constraints);

        return $constraint;
    }

    public static function anything(): IsAnything
    {
        return new IsAnything;
    }

    public static function isTrue(): IsTrue
    {
        return new IsTrue;
    }

    public static function callback(callable $callback): Callback
    {
        return new Callback($callback);
    }

    public static function isFalse(): IsFalse
    {
        return new IsFalse;
    }

    public static function isJson(): IsJson
    {
        return new IsJson;
    }

    public static function isNull(): IsNull
    {
        return new IsNull;
    }

    public static function isFinite(): IsFinite
    {
        return new IsFinite;
    }

    public static function isInfinite(): IsInfinite
    {
        return new IsInfinite;
    }

    public static function isNan(): IsNan
    {
        return new IsNan;
    }

    /**
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     */
    public static function attribute(Constraint $constraint, string $attributeName): Attribute
    {
        return new Attribute($constraint, $attributeName);
    }

    public static function contains($value, bool $checkForObjectIdentity = true, bool $checkForNonObjectIdentity = false): TraversableContains
    {
        return new TraversableContains($value, $checkForObjectIdentity, $checkForNonObjectIdentity);
    }

    public static function containsOnly(string $type): TraversableContainsOnly
    {
        return new TraversableContainsOnly($type);
    }

    public static function containsOnlyInstancesOf(string $className): TraversableContainsOnly
    {
        return new TraversableContainsOnly($className, false);
    }

    /**
     * @param int|string $key
     */
    public static function arrayHasKey($key): ArrayHasKey
    {
        return new ArrayHasKey($key);
    }

    public static function equalTo($value, float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false): IsEqual
    {
        return new IsEqual($value, $delta, $maxDepth, $canonicalize, $ignoreCase);
    }

    /**
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     */
    public static function attributeEqualTo(string $attributeName, $value, float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false): Attribute
    {
        return static::attribute(
            static::equalTo(
                $value,
                $delta,
                $maxDepth,
                $canonicalize,
                $ignoreCase
            ),
            $attributeName
        );
    }

    public static function isEmpty(): IsEmpty
    {
        return new IsEmpty;
    }

    public static function isWritable(): IsWritable
    {
        return new IsWritable;
    }

    public static function isReadable(): IsReadable
    {
        return new IsReadable;
    }

    public static function directoryExists(): DirectoryExists
    {
        return new DirectoryExists;
    }

    public static function fileExists(): FileExists
    {
        return new FileExists;
    }

    public static function greaterThan($value): GreaterThan
    {
        return new GreaterThan($value);
    }

    public static function greaterThanOrEqual($value): LogicalOr
    {
        return static::logicalOr(
            new IsEqual($value),
            new GreaterThan($value)
        );
    }

    public static function classHasAttribute(string $attributeName): ClassHasAttribute
    {
        return new ClassHasAttribute($attributeName);
    }

    public static function classHasStaticAttribute(string $attributeName): ClassHasStaticAttribute
    {
        return new ClassHasStaticAttribute($attributeName);
    }

    public static function objectHasAttribute($attributeName): ObjectHasAttribute
    {
        return new ObjectHasAttribute($attributeName);
    }

    public static function identicalTo($value): IsIdentical
    {
        return new IsIdentical($value);
    }

    public static function isInstanceOf(string $className): IsInstanceOf
    {
        return new IsInstanceOf($className);
    }

    public static function isType(string $type): IsType
    {
        return new IsType($type);
    }

    public static function lessThan($value): LessThan
    {
        return new LessThan($value);
    }

    public static function lessThanOrEqual($value): LogicalOr
    {
        return static::logicalOr(
            new IsEqual($value),
            new LessThan($value)
        );
    }

    public static function matchesRegularExpression(string $pattern): RegularExpression
    {
        return new RegularExpression($pattern);
    }

    public static function matches(string $string): StringMatchesFormatDescription
    {
        return new StringMatchesFormatDescription($string);
    }

    public static function stringStartsWith($prefix): StringStartsWith
    {
        return new StringStartsWith($prefix);
    }

    public static function stringContains(string $string, bool $case = true): StringContains
    {
        return new StringContains($string, $case);
    }

    public static function stringEndsWith(string $suffix): StringEndsWith
    {
        return new StringEndsWith($suffix);
    }

    public static function countOf(int $count): Count
    {
        return new Count($count);
    }

    /**
     * Fails a test with the given message.
     *
     * @throws AssertionFailedError
     */
    public static function fail(string $message = ''): void
    {
        self::$count++;

        throw new AssertionFailedError($message);
    }

    /**
     * Returns the value of an attribute of a class or an object.
     * This also works for attributes that are declared protected or private.
     *
     * @param