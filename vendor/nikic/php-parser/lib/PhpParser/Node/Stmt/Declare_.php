->semValue = new Expr\AssignOp\Coalesce($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            314 => function ($stackPos) {
                 $this->semValue = new Expr\PostInc($this->semStack[$stackPos-(2-1)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            315 => function ($stackPos) {
                 $this->semValue = new Expr\PreInc($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            316 => function ($stackPos) {
                 $this->semValue = new Expr\PostDec($this->semStack[$stackPos-(2-1)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            317 => function ($stackPos) {
             