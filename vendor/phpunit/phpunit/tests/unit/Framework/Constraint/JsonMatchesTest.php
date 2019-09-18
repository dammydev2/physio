ltPrinter',
                'testSuiteLoaderClass'                       => 'PHPUnit\Runner\StandardTestSuiteLoader',
                'defaultTestSuite'                           => 'My Test Suite',
                'verbose'                                    => false,
                'timeoutForSmallTests'                       => 1,
                'timeoutForMediumTests'                      => 10,
                'timeoutForLargeTests'                       => 60,
                'beStrictAboutResourceUsageDuringSmallTests' => false,
                'disallowTodoAnnotatedTests'                 => false,
                'failOnWarning'                              => false,
                'failOnRisky'                                => false,
                'ignoreDeprecatedCodeUnitsFromCodeCoverage'  => false,
                'executionOrder'                             => TestSuiteSorter::ORDER_DEFAULT,
                'executionOrderDefects'                      => TestSuiteSorter::ORDER_DEFAULT,
                'resolveDependencies'                        => false,
            ],
            $this->configuration->getPHPUnitConfiguration()
        );
    }

    public function testXincludeInConfiguration(): void
    {
        $configurationWithXinclude = Configuration::getInstance(
            TEST_FILES_PATH . 'configuration_xinclude.xml'
        );

        $this->assertConfigurationEquals(
            $this->configuration,
            $configurationWithXinclude
        );
    }

    /**
     * @ticket 1311
     */
    public function testWithEmptyConfigurations(): void
    {
        $emptyConfiguration = Configuration::getInstance(
            TEST_FILES_PATH . 'configuration_empty.xml'
        );

        $logging = $emptyConfiguration->getLoggingConfiguration();
        $this->assertEmpty($logging);

        $php = $emptyConfiguration->getPHPConfiguration();
        $this->assertEmpty($php['include_path']);

        $phpunit = $emptyConfiguration->getPHPUnitConfiguration();
        $this->assertArrayNotHasKey('bootstrap', $phpunit);
        $this->assertArrayNotHasKey('testSuiteLoaderFile', $phpunit);
        $this->assertArrayNotHasKey('printerFile', $phpunit);

        $suite = $emptyConfiguration->getTestSuiteConfiguration();
        $this->assertEmpty($suite->getGroups());

        $filter = $emptyConfiguration->getFilterConfiguration();
        $this->assertEmpty($filter['whitelist']['include']['directory']);
        $this->assertEmpty($filter['whitelist']['include']['file']);
        $this->assertEmpty($filter['whitelist']['exclude']['directory']);
        $this->assertEmpty($filter['whitelist']['exclude']['file']);
    }

    public function testGetTestSuiteNamesReturnsTheNamesIfDefined(): void
    {
        $configuration = Configuration::getInstance(
            TEST_FILES_PATH . 'configuration.suites.xml'
        );

        $names = $configuration->getTestSuiteNames();

        $this->assertEquals(['Suite One', 'Suite Two'], $names);
    }

    public function testTestSuiteConfigurationForASingleFileInASuite(): void
    {
        $configuration = Configuration::getInstance(
            TEST_FILES_PATH . 'configuration.one-file-suite.xml'
        );

        $config = $configuration->getTestSuiteConfiguration();
        $tests  = $config->tests();

        $this->assertCount(1, $tests);
    }

    /**
     * Asserts that the values in $actualConfiguration equal $expectedConfiguration.
     *
     * @throws Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    protected function assertConfigurationEquals(Configuration $expectedConfiguration, Configuration $actualConfiguration): void
    {
        $this->assertEquals(
            $expectedConfiguration->getFilterConfiguration(),
            $actualConfiguration->getFilterConfiguration()
        );

        $this->assertEquals(
            $expectedConfiguration->getGroupConfiguration(),
            $actualConfiguration->getGroupConfiguration()
        );

        $this->assertEquals(
            $expectedConfiguration->getListenerConfiguration(),
            $actualConfiguration->getListenerConfiguration()
        );

        $this->assertEquals(
            $expectedConfiguration->getLoggingConfiguration(),
            $actualConfiguration->getLoggingConfiguration()
        );

        $this->assertEquals(
            $expectedConfiguration->getPHPConfiguration(),
            $actualConfiguration->getPHPConfiguration()
        );

        $this->assertEquals(
            $expectedConfiguration->getPHPUnitConfiguration(),
            $actualConfiguration->getPHPUnitConfiguration()
        );

        $this->assertEquals(
            $expectedConfiguration->getTestSuiteConfiguration()->tests(),
            $actualConfigurati