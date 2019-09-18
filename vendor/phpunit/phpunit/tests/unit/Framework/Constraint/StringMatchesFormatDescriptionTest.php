tespace',
                [
                    'PHP' => [
                        'constraint' => '~5.6.22 || ~7.0.17',
                    ],
                    'PHPUnit' => [
                        'constraint' => '~5.6.22 || ~7.0.17',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider requirementsWithInvalidVersionConstraintsThrowsExceptionProvider
     *
     * @throws Warning
     */
    public function testGetRequirementsWithInvalidVersionConstraintsThrowsException($test): void
    {
        $this->expectException(Warning::class);
        Test::getRequirements(\RequirementsTest::class, $test);
    }

    public function requirementsWithInvalidVersionConstraintsThrowsExceptionProvider(): array
    {
        return [
            ['testVersionConstraintInvalidPhpConstraint'],
            ['testVersionConstraintInvalidPhpUnitConstraint'],
        ];
    }

    public function testGetRequirementsMergesClassAndMethodDocBlocks(): void
    {
        $expectedAnnotations = [
            'PHP'       => ['version' => '5.4', 'operator' => ''],
            'PHPUnit'   => ['version' => '3.7', 'operator' => ''],
            'OS'        => 'WINNT',
            'functions' => [
                'testFuncClass',
                'testFuncMethod',
            ],
            'extensions' => [
                'testExtClass',
                'testExtMethod',
            ],
        ];

        $this->assertEquals(
            $expectedAnnotations,
            Test::getRequirements(\RequirementsClassDocBlockTest::class, 'testMethod')
        );
    }

    /**
     * @dataProvider missingRequirementsProvider
     *
     * @throws Warning
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testGetMissingRequirements($test, $result): void
    {
        $this->assertEquals(
            $result,
            Test::getMissingRequirements(\RequirementsTest::class, $test)
        );
    }

    public function missingRequirementsProvider(): array
    {
        return [
            ['testOne',            []],
            ['testNine',           ['Function testFunc is required.']],
            ['testTen',            ['Extension testExt is required.']],
            ['testAlwaysSkip',     ['PHPUnit >= 1111111 is required.']],
            ['testAlwaysSkip2',    ['PHP >= 9999999 is required.']],
            ['testAlwaysSkip3',    ['Operating system matching /DOESNOTEXIST/i is required.']],
            ['testAllPossibleRequirements', [
                'PHP >= 99-dev is required.',
                'PHPUnit >= 9-dev is required.',
                'Operating system matching /DOESNOTEXIST/i is required.',
                'Function testFuncOne is required.',
                'Function testFunc2 is required.',
                'Setting "not_a_setting" must be "Off".',
                'Extension testExtOne is required.',
                'Extension testExt2 is required.',
                'Extension testExtThree >= 2.0 is required.',
            ]],
            ['testPHPVersionOperatorLessThan', ['PHP < 5.4 is required.']],
            ['testPHPVersionOperatorLessThanEquals', ['PHP <= 5.4 is required.']],
            ['testPHPVersionOperatorGreaterThan', ['PHP > 99 is required.']],
            ['testPHPVersionOperatorGreaterThanEquals', ['PHP >= 99 is required.']],
            ['testPHPVersionOperatorNoSpace', ['PHP >= 99 is required.']],
            ['testPHPVersionOperatorEquals', ['PHP = 5.4 is required.']],
            ['testPHPVersionOperatorDoubleEquals', ['PHP == 5.4 is required.']],
            ['testPHPUnitVersionOperatorLessThan', ['PHPUnit < 1.0 is required.']],
            ['testPHPUnitVersionOperatorLessThanEquals', ['PHPUnit <= 1.0 is required.']],
            ['testPHPUnitVersionOperatorGreaterThan', ['PHPUnit > 99 is required.']],
            ['testPHPUnitVersionOperatorGreaterThanEquals', ['PHPUnit >= 99 is required.']],
            ['testPHPUnitVersionOperatorEquals', ['PHPUnit = 1.0 is required.']],
            ['testPHPUnitVersionOperatorDoubleEquals', ['PHPUnit == 1.0 is required.']],
            ['testPHPUnitVersionOperatorNoSpace', ['PHPUnit >= 99 is required.']],
            ['testExtensionVersionOperatorLessThan', ['Extension testExtOne < 1.0 is required.']],
            ['testExtensionVersionOperatorLessThanEquals', ['Extension testExtOne <= 1.0 is required.']],
            ['testExtensionVersionOperatorGreaterThan', ['Extension testExtOne > 99 is required.']],
            ['testExtensionVersionOperatorGreaterThanEquals', ['Extension testExtOne >= 99 is required.']],
            ['testExtensionVersionOperatorEquals', ['Extension testExtOne = 1.0 is required.']],
            ['testExtensionVersionOperatorDoubleEquals', ['Extension testExtOne == 1.0 is required.']],
            ['testExtensionVersionOperatorNoSpace', ['Extension testExtOne >= 99 is required.']],
            ['testVersionConstraintTildeMajor', [
                'PHP version does not match the required constraint ~1.0.',
                'PHPUnit version does not match the required constraint ~2.0.',
            ]],
            ['testVersionConstraintCaretMajor', [
                'PHP version does not match the required constraint ^1.0.',
                'PHPUnit version does not match the required constraint ^2.0.',
            ]],
        ];
    }

    /**
     * @todo This test does not really test functionality of \PHPUnit\Util\Test
     */
    public function testGetProvidedDataRegEx(): void
    {
        $result = \preg_match(Test::REGEX_DATA_PROVIDER, '@dataProvider method', $matches);
        $this->assertEquals(1, $result);
        $this->assertEquals('method', $matches[1]);

        $result = \preg_match(Test::REGEX_DATA_PROVIDER, '@dataProvider class::method', $matches);
        $this->assertEquals(1, $result);
        $this->assertEquals('class::method', $matches[1]);

        $result = \preg_match(Test::REGEX_DATA_PROVIDER, '@dataProvider namespace\class::method', $matches);
        $this->assertEquals(1, $result);
        $this->assertEquals('namespace\class::method', $matches[1]);

        $result = \preg_match(Test::REGEX_DATA_PROVIDER, '@dataProvider namespace\namespace\class::method', $matches);
        $this->assertEquals(1, $result);
        $this->assertEquals('namespace\namespace\class::method', $matches[1]);

        $result = \preg_match(Test::REGEX_DATA_PROVIDER, '@dataProvider メソッド', $matches);
        $this->assertEquals(1, $result);
        $this->assertEquals('メソッド', $matches[1]);
    }

    /**
     * Check if all data providers are being merged.
     */
    public function testMultipleDataProviders(): void
    {
        $dataSets = Test::getProvidedData(\MultipleDataProviderTest::class, 'testOne');

        $this->assertCount(9, $dataSets);

        $aCount = 0;
        $bCount = 0;
        $cCount = 0;

        for ($i = 0; $i < 9; $i++) {
            $aCount += $dataSets[$i][0] != null ? 1 : 0;
            $bCount += $dataSets[$i][1] != null ? 1 : 0;
            $cCount += $dataSets[$i][2] != null ? 1 : 0;
        }

        $this->assertEquals(3, $aCount);
        $this->assertEquals(3, $bCount);
        $this->assertEquals(3, $cCount);
    }

    public function testMultipleYieldIteratorDataProviders(): void
    {
        $dataSets = Test::getProvidedData(\MultipleDataProviderTest::class, 'testTwo');

        $this->assertCount(9, $dataSets);

        $aCount = 0;
        $bCount = 0;
        $cCount = 0;

        for ($i = 0; $i < 9; $i++) {
            $aCount += $dataSets[$i][0] != null ? 1 : 0;
            $bCount += $dataSets[$i][1] != null ? 1 : 0;
            $cCount += $dataSets[$i][2] != null ? 1 : 0;
        }

        $this->assertEquals(3, $aCount);
        $this->assertEquals(3, $bCount);
        $this->assertEquals(3, $cCount);
    }

    public function testWithVariousIterableDataProviders(): void
    {
        $dataSets = Test::getProvidedData(\VariousIterableDataProviderTest::class, 'test');

        $this->assertEquals([
            ['A'],
            ['B'],
            ['C'],
            ['D'],
            ['E'],
            ['F'],
            ['G'],
            ['H'],
            ['I'],
        ], $dataSets);
    }

    public function testTestWithEmptyAnnotation(): void
    {
        $result = Test::getDataFromTestWithAnnotation("/**\n * @anotherAnnotation\n */");
        $this->assertNull($result);
    }

    public function testTestWithSimpleCase(): void
    {
        $result = Test::getDataFromTestWithAnnotation('/**
                                                                     * @testWith [1]
                                                                     */');
        $this->assertEquals([[1]], $result);
    }

    public function testTestWithMultiLineMultiParameterCase(): void
    {
        $result = Test::getDataFromTestWithAnnotation('/**
                                                                     * @testWith [1, 2]
                                                                     * [3, 4]
                                                                     */');
        $this->assertEquals([[1, 2], [3, 4]], $result);
    }

    public function testTestWithVariousTypes(): void
    {
        $result = Test::getDataFromTestWithAnnotation('/**
            * @testWith ["ab"]
            *           [true]
            *           [null]
         */');
        $this->assertEquals([['ab'], [true], [null]], $result);
    }

    public function testTestWithAnnotationAfter(): void
    {
        $result = Test::getDataFromTestWithAnnotation('/**
                                                                     * @testWith [1]
                                                                     *           [2]
                                                                     * @annotation
                                                                     */');
        $this->assertEquals([[1], [2]], $result);
    }

    public function testTestWithSimpleTextAfter(): void
    {
        $result = Test::getDataFromTestWithAnnotation('/**
                                                                     * @testWith [1]
                                                                     *           [2]
                                                                     * blah blah
                                                                     */');
        $this->assertEquals([[1], [2]], $result);
    }

    public function testTestWithCharacterEscape(): void
    {
        $result = Test::getDataFromTestWithAnnotation('/**
                                                                     * @testWith ["\"", "\""]
                                                                     */');
        $this->assertEquals([['"', '"']], $result);
    }

    public function testTestWithThrowsProperExceptionIfDatasetCannotBeParsed(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessageRegExp('/^The data set for the @testWith annotation cannot be parsed:/');

        Test::getDataFromTestWithAnnotation('/**
                                                           * @testWith [s]
                                                           */');
    }

    public function testTestWithThrowsProperExceptionIfMultiLineDatasetCannotBeParsed(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessageRegExp('/^The data set for the @testWith annotation cannot be parsed:/');

        Test::getDataFromTestWithAnnotation('/**
                                                           * @testWith ["valid"]
                                                           *           [invalid]
                                                           */');
    }

    /**
     * @todo