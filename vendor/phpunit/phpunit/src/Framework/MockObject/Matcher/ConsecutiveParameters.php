h'],
                            $dir['suffix'],
                            $dir['prefix']
                        );
                    }

                    foreach ($filterConfiguration['whitelist']['exclude']['file'] as $file) {
                        $this->codeCoverageFilter->removeFileFromWhitelist($file);
                    }
                }
            }
        }

        if ($codeCoverageReports > 0) {
            $codeCoverage = new CodeCoverage(
                null,
                $this->codeCoverageFilter
            );

            $codeCoverage->setUnintentionallyCoveredSubclassesWhitelist(
                [Comparator::class]
            );

            $codeCoverage->setCheckForUnintentionallyCoveredCode(
                $arguments['strictCoverage']
            );

            $codeCoverage->setCheckForMissingCoversAnnotation(
                $arguments['strictCoverage']
            );

            if (isset($arguments['forceCoversAnnotation'])) {
                $codeCoverage->setForceCoversAnnotation(
                    $arguments['forceCoversAnnotation']
                );
            }

            if (isset($arguments['ignoreDeprecatedCodeUnitsFromCodeCoverage'])) {
                $codeCoverage->setIgnoreDeprecatedCode(
                    $arguments['ignoreDeprecatedCodeUnitsFromCodeCoverage']
                );
            }

            if (isset($arguments['disableCodeCoverageIgnore'])) {
                $codeCoverage->setDisableIgnoredLines(true);
            }

            if (!empty($filterConfiguration['whitelist'])) {
                $codeCoverage->setAddUncoveredFilesFromWhitelist(
                    $filterConfiguration['whitelist']['addUncoveredFilesFromWhitelist']
                );

                $codeCoverage->setProcessUncoveredFilesFromWhitelist(
                    $filterConfiguration['whitelist']['processUncoveredFilesFromWhitelist']
                );
            }

            if (!$this->codeCoverageFilter->hasWhitelist()) {
                if (!$whitelistFromConfigurationFile && !$whitelistFromOption) {
                    $this->writeMessage('Error', 'No whitelist is configured, no code coverage will be generated.');
                } else {
                    $this->writeMessage('Error', 'Incorrect whitelist config, no code coverage will be generated.');
                }

                $codeCoverageReports = 0;

                unset($codeCoverage);
            }
        }

        if (isset($arguments['xdebugFilterFile'], $filterConfiguration)) {
            $this->write("\n");

            $script = (new XdebugFilterScriptGenerator)->generate($filterConfiguration['whitelist']);

            if ($arguments['xdebugFilterFile'] !== 'php://stdout' && $arguments['xdebugFilterFile'] !== 'php://stderr' && !Filesystem::createDirectory(\dirname($arguments['xdebugFilterFile']))) {
                $this->write(\sprintf('Cannot write Xdebug filter script to %s ' . \PHP_EOL, $arguments['xdebugFilterFile']));

                exit(self::EXCEPTION_EXIT);
            }

            \file_put_contents($arguments['xdebugFilterFile'], $script);

            $this->write(\sprintf('Wrote Xdebug filter script to %s ' . \PHP_EOL, $arguments['xdebugFilterFile']));

            exit(self::SUCCESS_EXIT);
        }

        $this->printer->write("\n");

        if (isset($codeCoverage)) {
            $result->setCodeCoverage($codeCoverage);

            if ($codeCoverageReports > 1 && isset($arguments['cacheTokens'])) {
                $codeCoverage->setCacheTokens($arguments['cache