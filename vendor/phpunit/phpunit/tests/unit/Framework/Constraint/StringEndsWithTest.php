ons' => ['testExtOne' => ['version' => '99', 'operator' => '>=']],
                ],
            ],
        ];
    }

    /**
     * @dataProvider requirementsWithVersionConstraintsProvider
     *
     * @throws Exception
     * @throws Warning
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testGetRequirementsWithVersionConstraints($test, array $result): void
    {
        $requirements = Test::getRequirements(\RequirementsTest::class, $test);

        foreach ($result as $type => $expected_requirement) {
            $this->assertArrayHasKey(
                "{$type}_constraint",
                $requirements
            );
            $this->assertArrayHasKey(
                'constraint',
                $requirements["{$type}_constraint"]
            );
            $this->assertInstanceOf(
                VersionConstraint::class,
                $requirements["{$type}_constraint"]['constraint']
            );
            $this->assertSame(
                $expected_requirement['constraint'],
                $requirements["{$type}_constraint"]['constraint']->asString()
            );
        }
    }

    public function requirementsWithVersionConstraintsProvider(): array
    {
        return [
            [
                'testVersionConstraintTildeMajor',
                [
                    'PHP' => [
                        'constraint' => '~1.0',
                    ],
                    'PHPUnit' => [
                        'constraint' => '~2.0',
                    ],
                ],
            ],
            [
                'testVersionConstraintCaretMajor',
                [
                    'PHP' => [
                        'constraint' => '^1.0',
                    ],
                    'PHPUnit' => [
                        'constraint' => '^2.0',
                    ],
                ],
            ],
            [
                'testVersionConstraintTildeMinor',
                [
                    'PHP' => [
                        'constraint' => '~3.4.7',
                    ],
                    'PHPUnit' => [
                        'constraint' => '~4.7.1',
                    ],
                ],
            ],
            [
                'testVersionConstraintCaretMinor',
                [
                    'PHP' => [
                        'constraint' => '^7.0.17',
                    ],
                    'PHPUnit' => [
                        'constraint' => '^4.7.1',
                    ],
                ],
            ],
            [
                'testVersionConstraintCaretOr',
                [
                    'PHP' => [
              