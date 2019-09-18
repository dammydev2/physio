 if (isset($loggingConfiguration['junit']) && !isset($arguments['junitLogfile'])) {
                $arguments['junitLogfile'] = $loggingConfiguration['junit'];
            }

            if (isset($loggingConfiguration['testdox-html']) && !isset($arguments['testdoxHTMLFile'])) {
                $arguments['testdoxHTMLFile'] = $loggingConfiguration['testdox-html'];
            }

            if (isset($loggingConfiguration['testdox-text']) && !isset($arguments['testdoxTextFile'])) {
                $arguments['testdoxTextFile'] = $loggingConfiguration['testdox-text'];
            }

            if (isset($loggingConfiguration['testdox-xml']) && !isset($arguments['testdoxXMLFile'])) {
                $arguments['testdoxXMLFile'] = $loggingConfiguration['testdox-xml'];
            }

            $testdoxGroupConfiguration = $arguments['configuration']->getTestdoxGroupConfiguration();

            if (isset($testdoxGroupConfiguration['include']) &&
                !isset($arguments['testdoxGroups'])) {
                $arguments['testdoxGroups'] = $testdoxGroupConfiguration['include'];
            }

            if (isset($testdoxGroupConfiguration['exclude']) &&
                !isset($arguments['testdoxExcludeGroups'])) {
                $arguments['testdoxExcludeGroups'] = $testdoxGroupConfiguration['exclude'];
            }
        }

        $arguments['addUncoveredFilesFromWhitelist']     