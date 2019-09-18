s->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            297 => function ($stackPos) {
                 $this->semValue = new Expr\AssignRef($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-4)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            298 => function ($stackPos) {
                 $this->semValue = new Expr\AssignRef($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-4)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            299 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            300 => function ($stackPos) {
                 $this->semValue = new Expr\Clone_($this->semStack[$stackPos-(2-2)], $