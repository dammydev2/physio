 {
                 $this->semValue = new Expr\StaticCall($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-3)], $this->semStack[$stackPos-(4-4)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            399 => function ($stackPos) {
                 $this->semValue = new Expr\StaticCall($this->semStack[$stackPos-(6-1)], $this->semStack[$stackPos-(6-4)], $this->semStack[$stackPos-(6-6)], $this->startAttributeStack[$stackPos-(6-1)] + $this->endAttributes);
            },
            400 => function ($stackPos) {
                 $this->semValue = $this->fixupPhp5StaticPropCall($this->semStack[$stackPos-(2-1)], $this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            401 => function ($stackPos) {
                 $this->semValue = new Expr\FuncCall($this->semStack[$stackPos-(2-1)], $this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            402 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayDimFetch($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            403 => function ($stackP