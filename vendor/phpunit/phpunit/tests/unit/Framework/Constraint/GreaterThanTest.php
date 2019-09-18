ependencyTest::class . '::testTwo',
                    \MultiDependencyTest::class . '::testThree',
                    \MultiDependencyTest::class . '::testFour',
                    \MultiDependencyTest::class . '::testFive',
                ],
            ],
            'duration-different-dependency-ignore' => [
                self::IGNORE_DEPENDENCIES,
                [
                    'testOne'   => 5,
                    'testTwo'   => 3,
                    'testThree' => 4,
                    'testFour'  => 1,
                    'testFive'  => 2,
                ],
                [
                    \MultiDependencyTest::class . '::testFour',
                    \MultiDependencyTest::class . '::testFive',
                    \MultiDependencyTest::class . '::testTwo',
                    \MultiDependencyTest::class . '::testThree',
                    \MultiDependencyTest::class . '::testOne',
                ],
            ],
            'duration-different-dependency-resolve' => [
                self::RESOLVE_DEPENDENCIES,
                [
                    'testOne'   => 5,
                    'testTwo'   => 3,
                    'testThree' => 4,
                    'testFour'  => 1,
                    'testFive'  => 2,
                ],
                [
                    \MultiDependencyTest::class . '::testFive',
                    \MultiDependencyTest::class . '::testTwo',
                    \MultiDependencyTest::class . '::testOne',
                    \MultiDependencyTest::class . '::testThree',
                    \MultiDependencyT