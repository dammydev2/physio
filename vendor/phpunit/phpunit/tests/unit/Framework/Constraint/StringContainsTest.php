            Test::getExpectedException(\ExceptionTest::class, 'testWithUnknowRegexMessageFromClassConstant')
        );
    }

    /**
     * @dataProvider requirementsProvider
     *
     * @throws Warning
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testGetRequirements($test, $result): void
    {
        $this->assertEquals(
            $result,
            Test::getRequirements(\RequirementsTest::class, $test)
        );
    }

    public function requirementsProvider(): array
    {
        return [
            ['testOne',    []],
            ['testTwo',    ['PHPUnit'    => ['version' => '1.0', 'operator' => '']]],
            ['testThree',  ['PHP'        => ['version' => '2.0', 'operator' => '']]],
            ['testFour',   [
                'PHPUnit'    => ['version' => '2.0', 'operator' => ''],
                'PHP'        => ['version' => '1.0', 'operator' => ''],
            ]],
            ['testFive',   ['PHP'        => ['version' => '5.4.0RC6', 'operator' => '']]],
            ['testSix',    ['PHP'        => ['version' => '5.4.0-alpha1', 'operator' => '']]],
            ['testSeven',  ['PHP'        => ['version' => '5.4.0beta2', 'operator' => '']]],
            ['testEight',  ['PHP'        => ['version' => '5.4-dev', 'operator' => '']]],
            ['testNine',   ['functions'  => ['testFunc']]],
            ['testTen',    ['extensions' => ['testExt']]],
            ['testEleven', [
                'OS'         => 'SunOS',
                'OSFAMILY'   => 'Solaris',
            ]],
            [
                'testSpace',
                [
                    'extensions' => ['spl'],
                    'OS'         => '.*',
                ],
            ],
            [
                'testAllPossibleRequirements',
                [
                    'PHP'       => ['version' => '99-dev', 'operator' => ''],
                    'PHPUnit'   => ['version' => '9-dev', 'operator' => ''],
                    'OS'        => 'DOESNOTEXIST',
                    'functions' => [
                        'testFuncOne',
                        'testFunc2',
                    ],
                    'setting'   => [
                        'not_a_setting' => 'Off',
                    ],
                    'extensions' => [
                        'testExtOne',
                        'testExt2',
                        'testExtThree',
                    ],
                    'extension_versions' => [
                        'testExtThree' => ['version' => '2.0', 'operator' => ''],
                    ],
                ],
            ],
            ['testSpecificExtensionVersion',
                [
                    'extension_versions' => ['testExt' => ['version' => '1.8.0', 'operator' => '']],
             