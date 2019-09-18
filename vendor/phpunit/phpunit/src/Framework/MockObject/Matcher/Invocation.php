                  $writer->process($codeCoverage, $arguments['coveragePHP']);

                    $this->printer->write(" done\n");
                    unset($writer);
                } catch (CodeCoverageException $e) {
                    $this->printer->write(
                        " failed\n" . $e->getMessage() . "\n"
                    );
                }
            }

            if (isset($arguments['coverageText'])) {
                if ($arguments['coverageText'] == 'php://stdout') {
                    $outputStream = $this->printer;
                    $colors       = $arguments['colors'] && $arguments['colors'] != ResultPrinter::COLOR_NEVER;
                } else {
                    $outputStream = new Printer($arguments['coverageText']);
                    $colors       = false;
                }

                $processor = new TextReport(
                    $arguments['reportLowUpperBound'],
                    $arguments['reportHighLowerBound'],
                    $arguments['coverageTextShowUncoveredFiles'],
                    $arguments['coverageTextShowOnlySummary']
                );

                $outputStream->write(
                    $processor->process($codeCoverage, $colors)
                );
            }

            if (isset($arguments['coverageXml'])) {
                $this->printer->write(
                    "\nGenerating code coverage report in PHPUnit XML format ..."
                );

                try {
                    $writer = new XmlReport(Version::id());
                    $writer->process($codeCoverage, $arguments['coverageXml']);

                    $this->printer->write(" done\n");
                   