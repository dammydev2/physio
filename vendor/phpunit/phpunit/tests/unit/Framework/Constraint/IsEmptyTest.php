ut checks should give a simple reverse order
            'reverse' => [
                TestSuiteSorter::ORDER_REVERSED,
                self::IGNORE_DEPENDENCIES,
                [
                    \MultiDependencyTest::class . '::testFive',
                    \MultiDependencyTest::class . '::testFour',
                    \MultiDependencyTest::class . '::testThree',
                    \MultiDependencyTest::class . '::testTwo',
                    \MultiDependencyTest::class . '::testOne',
                ],
            ],

            // Reversing with resolution still allows testFive to move to front, testTwo before testOne
            'resolve reverse' => [
                TestSuiteSorter::ORDER_REVERSED,
                self::RESOLVE_DEPENDENCIES,
                [
                    \MultiDependencyTest::class . '::testFive',
                    \MultiDependencyTest::class . '::testTwo',
                    \MultiDependencyTest::class . '::testOne',
                    \MultiDependencyTest::class . '::testThree',
                    \MultiDependencyTest::class . '::testFour',
                ],
            ],
        ];
    }

    /**
     * A @dataprovider for testing defects execution reordering options based on MultiDependencyTest
     * This class has the following relevant properties:
     * - it has five tests 'testOne' ... 'testFive'
     * - 'testThree' @depends on both 'testOne' and 'testTwo'
     * - 'testFour' @depends on 'MultiDependencyTest::testThree' to test FQN @depends
     * - 'testFive' has no dependencies
     */
    public function defectsSorterOptionsProvider(): array
    {
        return [
            // The most simple situation should work as normal
            'default, no defects' 