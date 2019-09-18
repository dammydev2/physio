_put_contents($tmpFilename, $xml);

        $configurationInstance = Configuration::getInstance($tmpFilename);
        $this->assertFalse($configurationInstance->hasValidationErrors(), 'option causes validation error');

        $configurationValues   = $configurationInstance->getPHPUnitConfiguration();
        $this->assertEquals($expected, $configurationValues[$optionName]);

        @\unlink($tmpFilename);
    }

    public function configurationRootOptionsProvider(): array
    {
        return [
            'executionOrder default'                                        => ['executionOrder', 'default', TestSuiteSorter::ORDER_DEFAULT],
            'executionOrder random'                                         => ['executionOrder', 'random', TestSuiteSorter::ORDER_RANDOMIZED],
            'executionOrder reverse'                                        => ['executionOrder', 'reverse', TestSuiteSorter::ORDER_REVERSED],
            'cacheResult false'                                             => ['cacheResult', 'false', fa