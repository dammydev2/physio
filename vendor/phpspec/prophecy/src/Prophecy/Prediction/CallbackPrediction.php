 ($this->codeUnitsByLine[$lineNumber] as &$codeUnit) {
                        $codeUnit['executedLines']++;
                    }

                    unset($codeUnit);

                    $this->numExecutedLines++;
                }
            }
        }

        foreach ($this->traits as &$trait) {
            foreach ($trait['methods'] as &$method) {
                if ($method['executableLines'] > 0) {
                    $method['coverage'] = ($method['executedLines'] /
                            $method['executableLines']) * 100;
                } else {
                    $method['coverage'] = 100;
                }

                $method['crap'] = $this->crap(
                    $method['ccn'],
                    $method['coverage']
                );

                $trait['ccn'] += $method['ccn'];
            }

            unset($method);

            if ($trait['executableLines'] > 0) {
                $trait['coverage'] = ($trait['executedLines'] /
                        $trait['executableLines']) * 100;

                if ($trait['coverage'] === 100) {
                    $this->numTestedClasses++;
                }
            } else {
                $trait['coverage'] = 100;
            }

            $trait['crap'] = $this->crap(
                $trait['ccn'],
                $trait['coverage']
            );
        }

        unset($trait);

        foreach ($this->classes as &$class) {
            foreach ($class['methods'] as &$method) {
                if ($method['executableLines'] > 0) {
                    $method['coverage'] = ($method['executedLines'] /
                            $method['executableLines']) * 100;