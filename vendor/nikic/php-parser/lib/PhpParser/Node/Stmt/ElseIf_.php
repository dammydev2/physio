ackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            347 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\GreaterOrEqual($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            348 => function ($stackPos) {
                 $this->semValue = new Expr\Instanceof_($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            349 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            350 => function ($stackPos) {
                 $this->semValue = $this->