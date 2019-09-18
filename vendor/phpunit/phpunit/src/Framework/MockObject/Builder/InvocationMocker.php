true;

                    break;

                case '--stop-on-defect':
                    $this->arguments['stopOnDefect'] = true;

                    break;

                case '--stop-on-error':
                    $this->arguments['stopOnError'] = true;

                    break;

                case '--stop-on-failure':
                    $this->arguments['stopOnFailure'] = true;

                    break;

                case '--stop-on-warning':
                    $this->arguments['stopOnWarning'] = true;

                    break;

                case '--stop-on-incomplete':
                    $this->arguments['stopOnIncomplete'] = true;

                    break;

                case '--stop-on-risky':
                    $this->arguments['stopOnRisky'] = true;

                    break;

                case '--stop-on-skipped':
                    $this->arguments['stopOnSkipped'] = true;

                    break;

                case '--fail-on-warning':
                    $this->arguments['failOnWarning'] = true;

                    break;

                case '--fail-on-risky':
                    $this->arguments['failOnRisky'] = true;

                    break;

                case '--teamcity':
                    $this->arguments['printer'] = TeamCity::class;

                    break;

                case '--testdox':
                    $this->arguments['printer'] = CliTestDoxPrinter::class;

                    break;

                case '--testdox-group':
                    $this->arguments['testdoxGroups'] = \explode(
                        ',',
                        $option[1]
                    );

                    break;

                case '--testdox-exclude-group':
                    $this->arguments['testdoxExcludeGroups'] = \explode(
                        ',',
                        $option[1]
                    );

                    break;

                case '--testdox-html':
                    $this->arguments['testdoxHTMLFile'] = $option[1];

                    break;

                case '--testdox-text':
                    $this->arguments['testdoxTextFile'] = $option[1];

                    break;

                case '--testdox-xml':
                    $this->arguments['testdoxXMLFile'] = $option[1];

                    break;

                case '--no-configuration':
                    $this->arguments['useDefaultConfiguration'] = false;

                    break;

                case '--no-extensions':
                    $this->arguments['noExtensions'] = true;

                    break;

                case '--no-coverage':
                    $this->arguments['noCoverage'] = true;

                    break;

                case '--no-logging':
                    $this->arguments['noLogging'] = true;

                    break;

                case '--globals-backup':
                    $this->arguments['backupGlobals'] = true;

                    break;

                case '--static-backup':
                    $this->arguments['backupStaticAttributes'] = true;

                    break;

                case 'v':
                case '--verbose':
                    $this->arguments['verbose'] = true;

                    break;

                case '--atleast-version':
                    if (\version_compare(Version::id(), $option[1], '>=')) {
                        exit(TestRunner::SUCCESS_EXIT);
                    }

                    exit(TestRunner::FAILURE_EXIT);

                    break;

                case '--version':
                    $this->printVersionString();
                    exit(TestRunner::SUCCESS_EXIT);

                    break;

                case '--dont-report-useless-tests':
                    $this->arguments['reportUselessTests'] = false;

                    break;

                case '--strict-coverage':
                    $this->arguments['strictCoverage'] = true;

                    break;

                case '--disable-coverage-ignore':
                    $this->arguments['disableCodeCoverageIgnore'] = true;

                    break;

                case '--strict-global-state':
                    $this->arguments['beStrictAboutChangesToGlobalState'] = true;

                    break;

                case '--disallow-test-output':
                    $this->arguments['disallowTestOutput'] = true;

                    break;

                case '--disallow-resource-usage':
                    $this->arguments['beStrictAboutResourceUsageDuringSmallTests'] = true;

                    break;

                case '--default-time-limit':
                    $this->arguments['defaultTimeLimit'] = (int) $option[1];

                    break;

                case '--enforce-time-limit':
                    $this->arguments['enforceTimeLimit'] = true;

                    break;

                case '--disallow-todo-tests':
                    $this->arguments['disallowTodoAnnotatedTests'] = true;

                    break;

                case '--reverse-list':
                    $this->arguments['reverseList'] = true;

                    break;

                case '--check-version':
                    $this->handleVersionCheck();

                    break;

                case '--whitelist':
                    $this->arguments['whitelist'] = $option[1];

                    break;

                case '--random-order':
                    $this->handleOrderByOption('random');

                    break;

                case '--random-order-seed':
                    $this->arguments['randomOrderSeed'] = (int) $option[1];

                    break;

                case '--resolve-dependencies':
                    $this->handleOrderByOption('depends');

                    break;

                case '--ignore-dependencies':
                    $this->arguments['resolveDependencies'] = false;

                    break;

                case '--reverse-order':
                    $this->handleOrderByOption('reverse');

                    break;

                case '--dump-xdebug-filter':
                    $this->arguments['xdebugFilterFile'] = $option[1];

                    break;

                default:
                    $optionName = \str_replace('--', '', $option[0]);

                    $handler = null;

                    if (isset($this->l