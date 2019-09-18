                     4 => null,
                        5 => new \stdClass,
                        6 => TEST_FILES_PATH . 'MyTestFile.php',
                        7 => TEST_FILES_PATH . 'MyRelativePath',
                    ],
                ],
                [
                    'class'     => 'IncludePathExtension',
                    'file'      => __FILE__,
                    'arguments' => [],
                ],
                [
                    'class'     => 'CompactArgumentsExtension',
                    'file'      => '/CompactArgumentsExtension.php',
                    'arguments' => [
                        0 => 42,
                    ],
                ],
            ],
            $this->configuration->getExtensionConfiguration()
        );

        \ini_set('include_path', $includePath);
    }

    public function testLoggingConfigurationIsReadCorrectly(): void
    {
        $this->assertEquals(
            [
                'lowUpperBound'                  => '50',
                'highLowerBoun