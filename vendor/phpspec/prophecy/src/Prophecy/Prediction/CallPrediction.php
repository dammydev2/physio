      ];

            foreach ($class['methods'] as $methodName => $method) {
                if (\strpos($methodName, 'anonymous') === 0) {
                    continue;
                }

                $this->classes[$className]['methods'][$methodName] = $this->newMethod($methodName, $method, $link);

                foreach (\range($method['startLine'], $method['endLine']) as $lineNumber) {
                    $this->codeUnitsByLine[$lineNumber] = [
                        &$this->classes[$className],
                        &$this->classes[$className]['methods'][$methodName],
                    ];
                }
            }
        }
    }

    private function processTraits(\PHP_Token_Stream $tokens): void
    {
        $traits = $tokens->getTraits();
        $link   = $this->getId() . '.html#';

        foreach ($traits as $traitName => $trait) {
            $this->traits[$traitName] = [
                'traitName'       => $traitName,
                'methods'         => [],
                'startLine'       => $trait['startLine'],
                'executableLines' => 0,
                'executedLines'   => 0,
                'ccn'             => 0,
                'coverage'        => 0,
                'crap'            => 0,
                'package'         => $trait['package'],
                'link'            => $link . $trait['startLine'],
            ];

            foreach ($trait['methods'] as $methodName => $method) {
                if (\strpos($methodName, 'anonymous') === 0) {
                    continue;
                }

                $this->traits[$traitName]['methods'][$methodName] = $this->newMethod($methodName, $method, $link);

                foreach (\range($method['startLine'], $method['endLine']) as $lineNumber) {
                    $this->codeUnitsByLine[$lineNumber] = [
                        &$this->traits[$traitName],
                        &$this->traits[$traitName]['methods'][$methodName],
                    ];
                }
            }
        }
    }

    private function processFunctions(\PHP_Token_Stream $tokens): void
    {
        $functions = $tokens->getFunctions();
        $link      = $this->getId() . '.html#';

        foreach ($functions as $functionName => $function) {
            if (\strpos($functionName, 'anonymous') === 0) {
                continue;
            }

 