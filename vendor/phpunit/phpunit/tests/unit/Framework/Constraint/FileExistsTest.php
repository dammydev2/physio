);
        $sorter->reorderTestsInSuite($suite, TestSuiteSorter::ORDER_RANDOMIZED, true, TestSuiteSorter::ORDER_DEFAULT);

        $expectedOrder = [
            \MultiDependencyTest::class . '::testTwo',
            \MultiDependencyTest::class . '::testFive',
            \MultiDependencyTest::class . '::testOne',
            \MultiDependencyTest::class . '::testThree',
            \MultiDependencyTest::class . '::testFour',
        ];

        $this->assertSame($expectedOrder, $sorter->getExecutionOrder());
    }

    /**
     * @dataProvider orderDurationWithoutCacheProvider
     */
    public function testOrderDurationWithoutCache(bool $resolveDependencies, array $expected): void
    {
        $suite = new TestSuite;

        $suite->addTestSuite(\MultiDependencyTest::class);

        $sorter = new TestSuiteSorter;

        $sorter->reorderTestsInSuite(
            $suite,
            TestSuiteSorter::ORDER_DURATION,
            $resolveDependencies,
            TestSuiteSorter::ORDER_DEFAULT
        );

        $this->assertSame($expected, $sorter->getExecutionOrder());
    }

    public function orderDurationWithoutCacheProvider(): array
    {
        return [
            'dependency-ignore' => [
                self::IGNORE_DEPENDENCIES,
                [
                    \MultiDependencyTest::class . '::testOne',
                    \MultiDependencyTest::class . '::testTwo',
                    \MultiDependencyTest::class . '::testThree',
                    \MultiDependencyTest::class . '::