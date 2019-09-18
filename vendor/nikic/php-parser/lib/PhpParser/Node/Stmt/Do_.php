          },
            330 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Div($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            331 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Mod($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            332 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\ShiftLeft($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            333 => function ($stackPos) {
