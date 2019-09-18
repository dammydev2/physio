       $this->semValue = new Expr\BinaryOp\Minus($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            456 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Mul($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            457 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Div($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            458 => function ($stackPos) {
                 $this->semValue = new Expr\BinaryOp\Mod($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            459 => function ($stackPos) {
          