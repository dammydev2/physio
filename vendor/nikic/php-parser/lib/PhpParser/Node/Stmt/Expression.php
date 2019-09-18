lue = new Expr\Cast\Array_($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            365 => function ($stackPos) {
                 $this->semValue = new Expr\Cast\Object_($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            366 => function ($stackPos) {
                 $this->semValue = new Expr\Cast\Bool_($this->semStack[$stackPos-(2-2)], $this->startAttributeStack[$stackPos-(2-1)] + $this->endAttributes);
            },
            367 => function ($stackPos) {
                 $this->semValue = new Expr\Cast\Unset_($this->semStack[$stackPos-(2-2)], $this->st