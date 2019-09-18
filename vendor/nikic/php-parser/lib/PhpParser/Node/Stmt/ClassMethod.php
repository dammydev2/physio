         $this->semValue = is_array($this->semStack[$stackPos-(1-1)]) ? $this->semStack[$stackPos-(1-1)] : array($this->semStack[$stackPos-(1-1)]);
            },
            208 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(4-2)];
            },
            209 => function ($stackPos) {
                 $this->semValue = array();
            },
            210 => function ($stackPos) {
                 $this->semStack[$stackPos-(2-1)][] = $this->semStack[$stackPos-(2-2)]; $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            211 => function ($stackPos) {
                 $this->semValue = new Stmt\ElseIf_($this->semStack[$stackPos-(3-2)], is_array($this->semStack[$stackPos-(3-3)]) ? $this->semStack[$stackPos-(3-3)] : array($this->semStack[$stackPos-(3-3)]), $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            212 => function ($stackPos) {
                 $this->semValue = array();
            },
            213 => function ($stackPos) {
                 $this->semStack[$stackPos-(2-1)][] = $this->semStack[$stackPos-(2-2)]; $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            214 => function ($stackPos) {
                 $this->semValue = new Stmt\ElseIf_($this->semStack[$stackPos-(4-2)], $this->semStack[$stackPos-(4-4)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            215 => function ($stackPos) {
                 $this->semValue = null;
            },
            216 => function ($stackPos) {
                 $this->semValue = new Stmt\Else_(is_array($this->semStack[$stackPos-(2-2)]) ? $this->semStack[$stackPos-(2-2)] : array($this->semStack[$stackPos-(2-2)]), $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            217 => function ($stackPos) {
                 $this->semValue = null;
            },
            218 => function ($stackPos) {
                 $this->semValue = new Stmt\Else_($this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            219 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)], false);
            },
            220 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(2-2)], true);
            },
            221 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)], false);
            },
            222 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            223 => function ($stackPos) {
                 $this->semValue = array();
            },
            224 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            225 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            226 => function ($stackPos) {
                 $this->semValue = new Node\Param($this->semStack[$stackPos-(4-4)], null, $this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-2)], $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes); $this->checkParam($this->semValue);
            },
            227 => function ($stackPos) {
                 $this->semValue = new Node\Param($this->semStack[$stackPos-(6-4)], $this->semStack[$stackPos-(6-6)], $this->semStack[$stackPos-(6-1)], $this->semStack[$stackPos-(6-2)], $this->semStack[$stackPos-(6-3)], $this->startAttributeStack[$stackPos-(6-1)] + $this->endAttributes); $this->checkParam($this->semValue);
            },
            228 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            229 => function ($stackPos) {
                 $this->semValue = new Node\Identifier('array', $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            230 => function ($stackPos) {
                 $this->semValue = new Node\Identifier('callable', $this->startAttributeStack[$stackPos-(