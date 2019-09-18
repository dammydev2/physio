              break;

                case '--filter':
                    $this->arguments['filter'] = $option[1];

                    break;

                case '--testsuite':
                    $this->arguments['testsuite'] = $option[1];

                    break;

                case '--generate-configuration':
                    $this->printVersionString();

                    print 'Generating phpunit.xml in ' . \getcwd() . \PHP_EOL . \PHP_EOL;

                    print 'Bootstrap script (relative to path shown above; default: vendor/autoload.php): ';
                    $bootstrapScript = \trim(\fgets(\STDIN));

                    print 'Tests directory (relative to path shown above; default: tests): ';
                    $testsDirectory = \trim(\fgets(\STDIN));

                    print 'Source directory (relative to path show