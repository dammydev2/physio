      [
                    \MultiDependencyTest::class . '::testFive',
                    \MultiDependencyTest::class . '::testOne',
                    \MultiDependencyTest::class . '::testTwo',
                    \MultiDependencyTest::class . '::testThree',
                    \MultiDependencyTest::class . '::testFour',
                ],
            ],

            // Defects in testFive and testTwo, but the faster testFive should be run first
            'default, testTwo testFive skipped' => [
                TestSuiteSorter::ORDER_DEFAULT,
                self::IGNORE_DEPENDENCIES,
                [
                    'testOne'   => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                    'testTwo'   => ['state' => BaseTestRunner::STATUS_SKIPPED, 'time' => 1],
                    'testThree' => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                    'testFour'  => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                    'testFive'  => ['state' => BaseTestRunner::STATUS_SKIPPED, 'time' => 0],
                ],
                [
                    \MultiDependencyTest::class . '::testFive',
                    \MultiDependencyTest::class . '::testTwo',
                    \MultiDependencyTest::class . '::testOne',
                    \MultiDependencyTest::class . '::testThree',
                    \MultiDependencyTest::class . '::testFour',
                ],
            ],

            // Skipping testThree will move it to the front when ignoring dependencies
            'default, testThree skipped' => [
                TestSuiteSorter::ORDER_DEFAULT,
                self::IGNORE_DEPENDENCIES,
                [
                    'testOne'   => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                    'testTwo'   => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                    'testThree' => ['state' => BaseTestRunner::STATUS_SKIPPED, 'time' => 1],
                    'testFour'  => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                    'testFive'  => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                ],
                [
                    \MultiDependencyTest::class . '::testThree',
                    \MultiDependencyTest::class . '::testOne',
                    \MultiDependencyTest::class . '::testTwo',
                    \MultiDependencyTest::class . '::testFour',
                    \MultiDependencyTest::class . '::testFive',
                ],
            ],

            // Skipping testThree will move it to the front but behind its dependencies
            'default resolve, testThree skipped' => [
                TestSuiteSorter::ORDER_DEFAULT,
                self::RESOLVE_DEPENDENCIES,
                [
                    'testOne'   => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                    'testTwo'   => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                    'testThree' => ['state' => BaseTestRunner::STATUS_SKIPPED, 'time' => 1],
                    'testFour'  => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                    'testFive'  => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                ],
                [
                    \MultiDependencyTest::class . '::testOne',
                    \MultiDependencyTest::class . '::testTwo',
                    \MultiDependencyTest::class . '::testThree',
                    \MultiDependencyTest::class . '::testFour',
                    \MultiDependencyTest::class . '::testFive',
                ],
            ],

            // Skipping testThree will move it to the front and keep the others reversed
            'reverse, testThree skipped' => [
                TestSuiteSorter::ORDER_REVERSED,
                self::IGNORE_DEPENDENCIES,
                [
                    'testOne'   => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                    'testTwo'   => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                    'testThree' => ['state' => BaseTestRunner::STATUS_SKIPPED, 'time' => 1],
                    'testFour'  => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                    'testFive'  => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                ],
                [
                    \MultiDependencyTest::class . '::testThree',
                    \MultiDependencyTest::class . '::testFive',
                    \MultiDependencyTest::class . '::testFour',
                    \MultiDependencyTest::class . '::testTwo',
                    \MultiDependencyTest::class . '::testOne',
                ],
            ],

            // Demonstrate a limit of the dependency resolver: after sorting defects to the front,
            // the resolver will mark testFive done before testThree because of dependencies
            'default resolve, testThree skipped, testFive fast' => [
                TestSuiteSorter::ORDER_DEFAULT,
                self::RESOLVE_DEPENDENCIES,
                [
                    'testOne'   => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                    'testTwo'   => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                    'testThree' => ['state' => BaseTestRunner::STATUS_SKIPPED, 'time' => 0],
                    'testFour'  => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                    'testFive'  => ['state' => BaseTestRunner::STATUS_SKIPPED, 'time' => 1],
                ],
                [
                    \MultiDependencyTest::class . '::testFive',
                    \MultiDependencyTest::class . '::testOne',
                    \MultiDependencyTest::class . '::testTwo',
                    \MultiDependencyTest::class . '::testThree',
                    \MultiDependencyTest::class . '::testFour',
                ],
            ],

            // Torture test
            // - incomplete TestResultCache
            // - skipped testThree: will move it to the front as far as possible
            // - testOne and testTwo are required before testThree, but can be reversed locally
            // - testFive is independent will remain reversed up front
            'reverse resolve, testThree skipped' => [
                TestSuiteSorter::ORDER_REVERSED,
                self::RESOLVE_DEPENDENCIES,
                [
                    'testOne'   => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                    'testTwo'   => ['state' => BaseTestRunner::STATUS_PASSED, 'time' => 1],
                    'testThree' => ['state' => BaseTestRunner::STATUS_SKIPPED, 'time' => 1],
                ],
                [
                  