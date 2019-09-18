 [
                                'path'   => '/path/to/files',
                                'prefix' => '',
                                'suffix' => '.php',
                                'group'  => 'DEFAULT',
                            ],
                        ],
                        'file' => [
                            0 => '/path/to/file',
                            1 => '/path/to/file',
                        ],
                    ],
                    'exclude' => [
                        'directory' => [
                            0 => [
                                'path'   => '/path/to/files',
                                'prefix' => '',
                                'suffix' => '.php',
                                'group'  => 'DEFAULT',
                            ],
                        ],
                        'file' => [
                            0 => '/path/to/file',
                        ],
                    ],
                ],
            ],
            $this->configuration->getFilterConfiguration()
        );
    }

    public function testGroupConfigurationIsReadCorrectly(): void
    {
        $this->assertEquals(
            [
                'include' => [
                    0 => 'name',
                ],
                'exclude' => [
                    0 => 'name',
                ],
            ],
            $this->configuration->getGroupConfiguration()
        );
    }

    public function testTestdoxGroupConfigurationIsReadCorrectly(): void
    {
        $this->assertEquals(
            [
                'include' => [
                    0 => 'name',
                ],
                'exclude' => [
                    0 => 'name',
                ],
            ],
            $this->configuration->getTestdoxGroupConfiguration()
        );
    }

    public function testListenerConfigurationIsReadCorrectly(): void
    {
        $dir         = __DIR__;
        $includePath = \ini_get('include_path');

        \ini_set('include_path', $dir . \PATH_SEPARATOR . $includePath);

        $this->assertEquals(
            [
                0 => [
                    'class'     => 'MyListener',
                    'file'      => '/optional/path/to/MyListener.php',
                    'arguments' => [
                        0 => [
                            0 => 'Sebastian',
                        ],
                        1 => 22,
                        2 => 'April',
                        3 => 19.78,
                        4 => null,
                        5 => new \stdClass,
                        6 => TEST_FILES_PATH . 'MyTestFile.php',
                        7 => TEST_FILES_PATH . 'MyRelativePath',
                        8 => true,
                    ],
                ],
                [
                    'class'     =