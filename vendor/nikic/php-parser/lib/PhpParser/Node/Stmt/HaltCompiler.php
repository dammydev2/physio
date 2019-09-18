tributes);
            },
            472 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Greater($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            473 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\GreaterOrEqual($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            474 => function ($stackPos) {
                 $this->semValue = new Expr\Ternary($this->semStack[$stackPos-(5-1)], $this->semStack[$stackPos-(5-3)], $this->semStack[$stackPos-(5-5)], $this->startAttr