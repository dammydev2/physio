rguments['stopOnSkipped']) {
            $result->stopOnSkipped(true);
        }

        if ($arguments['stopOnDefect']) {
            $result->stopOnDefect(true);
        }

        if ($arguments['registerMockObjectsFromTestArgumentsRecursively']) {
            $result->setRegisterMockObjectsFromTestArgumentsRecursively(true);
        }

        if ($this->printer === null) {
            if (isset($arguments['printer']) &&
                $arguments['printer'] instanceof Printer) {
                $this->printer = $arguments['printer'];
            } else {
                $printerClass = ResultPrinter::class;

                if (isset($arguments['printer']) && \is_string($arguments['printer']) && \class_exists($arguments['printer'], false)) {
                    $class = new ReflectionClass($arguments['printer']);

                    if ($class->isSubclassOf(ResultPrinter::class)) {
                        $printerClass = $arguments['printer'];
                    }
                }

                $this->printer = new $printerClass(
                    (isset($arguments['stderr']) && $arguments['stderr'] === true) ? 'php://stderr' : null,
                    $arguments['verbose'],
                    $arguments['colors'],
                    $arguments['debug'],
                    $arguments['columns'],
                    $arguments['reverseList']
                );

                if (isset($originalExecutionOrder) && ($this->printer instanceof CliTestDoxPrinter)) {
                    /* @var CliTestDoxPrinter */
                    $this->printer->setOriginalExecutionOrder($originalExecutionOrder);
                }
            }
        }

        $this->printer->write(
            Version::getVersionString() . "\n"
        );

        self::$versionStringPrinted = true;

        if ($arguments['verbose']) {
            $runtime = $this->runtime->getNameWithVersion();

            if ($this->runtime->hasXdebug()) {
                $runtime .= \sprintf(
                    ' with Xdebug %s',
                    \phpversion('xdebug')
                );
            }

            $this->writeMessage('Runtime', $runtime);

            if (isset($arguments['configuration'])) {
                $this->writeMessage(
                    'Configuration',
                    $arguments['configuration']->getFilename()
                );
            }

            foreach ($arguments['loadedExtensions'] as $extension) {
                $this->writeMessage(
                    'Extension',
                    $extension
                );
            }

            foreach ($arguments['notLoadedExtensions'] as $extension) {
                $this->writeMessage(
                    'Extension',
                    $extension
                );
            }
        }

        if ($arguments['executionOrder'] === TestSuiteSorter::ORDER_RANDOMIZED) {
            $this->writeMessage(
                'Random seed',
                $arguments['randomOrderSeed']
            );
        }

        if (isset($tooFewColumnsRequested)) {
            $this->writeMessage('Error', 'Less than 16 columns requested, number of columns set to 16');
        }

        if ($this->runtime->discardsComments()) {
            $this->writeMessage('Warning', 'opcache.save_comments=0 set; annotations will not work');
        }

        if (isset($arguments['configuration']) && $arguments['configuration']->hasValidationErrors()) {
            $this->write(
                "\n  Warning - The configuration file did not pass validation!\n  The following problems have been detected:\n"
            );

            foreach ($arguments['configuration']->getValidationErrors() as $line => $errors) {
                $this->write(\sprintf("\n  Line %d:\n", $line));

                foreach ($errors as $msg) {
                    $this->write(\sprintf("  - %s\n", $msg));
                }
            }
            $this->write("\n  Test results may not be as expected.\n\n");
        }

        foreach ($arguments['listeners'] as $listener) {
            $result->addListener($listener);
        }

        $result->addListener($this->printer);

        $codeCoverageReports = 0;

        if (!isset($arguments['noLogging'])) {
            if (isset($arguments['testdoxHTMLFile'])) {
                $result->addListener(
                    new HtmlResultPrinter(
                        $arguments['testdoxHTMLFile'],
                        $arguments['testdoxGroups'],
                        $arguments['testdoxExcludeGroups']
                    )
                );
            }

            if (isset($arguments['testdoxTextFile'])) {
                $result->addListener(
                    new TextResultPrinter(
                        $arguments['testdoxTextFile'],
                        $arguments['testdoxGroups'],
                        $arguments['testdoxExcludeGroups']
                    )
                );
            }

            if (isset($arguments['testdoxXMLFile'])) {
                $result->addListener(
                    new XmlResultPrinter(
                        $arguments['testdoxXMLFile']
                    )
                );
            }

            if (isset($arguments['teamcityLogfile'])) {
                $result->addListener(
                    new TeamCity($arguments['teamcityLogfile'])
                );
            }

            if (isset($arguments['junitLogfile'])) {
                $result->addListener(
                    new JUnit(
                        $arguments['junitLogfile'],
                        $arguments['reportUselessTests']
                    )
                );
            }

            if (isset($arguments['coverageClover'])) {
                $codeCoverageReports++;
            }

            if (isset($arguments['coverageCrap4J'])) {
                $codeCoverageReports++;
            }

            if (isset($arguments['coverageHtml'])) {
                $codeCoverageReports++;
            }

            if (isset($arguments['coveragePHP'])) {
                $codeCoverageReports++;
            }

