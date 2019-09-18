> function ($stackPos) {
                 $this->semValue = $this->parseDocString($this->semStack[$stackPos-(2-1)], '', $this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes, $this->startAttributeStack[$stackPos-(2-2)] + $this->endAttributeStack[$stackPos-(2-2)], false);
            },
            439 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            440 => function ($stackPos) {
                 $this->semValue = new Expr\ClassConstFetch($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            441 => function ($stackPos) {
