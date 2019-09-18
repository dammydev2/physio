             $class['package']['category']
                    );
                }

                if (!empty($class['package']['package'])) {
                    $xmlClass->setAttribute(
                        'package',
                        $class['package']['package']
                    );
                }

                if (!empty($class['package']['subpackage'])) {
                    $xmlClass->setAttribute(
                        'subpackage',
                        $class['package']['subpackage']
                    );
                }

                $xmlFile->appendChild($xmlClass);

                $xmlMetrics = $xmlDocument->createElement('metrics');
                $xmlMetrics->setAttribute('complexity', $class['ccn']);
                $xmlMetrics->setAttribute('methods', $classMethods);
                $xmlMetrics->setAttribute('coveredmethods', $coveredMethods);
                $xmlMetrics->setAttribute('conditionals', 0);
                $xmlMetrics->setAttribute('coveredconditionals', 0);
                $xmlMetrics->setAttribute('statements', $classStatements);
                $xmlMetrics->setAttribute('coveredstatements', $coveredClassStatements);
                $xmlMetrics->setAttribute('elements', $classMethods + $classStatements /* + conditionals */);
                $xmlMetrics->setAttribute('coveredelements', $coveredMethods + $coveredClassStatements /* + coveredconditionals */);
                $xmlClass->appendChild($xmlMetrics);
            }

            foreach ($coverageData as $line => $data) {
                if ($data === null || isset($lines[$line])) {
                    continue;
                }

