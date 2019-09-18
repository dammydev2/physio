StaticPropertyFetch($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            513 => function ($stackPos) {
                 $this->semValue = new Expr\StaticPropertyFetch($this->semStack[$stackPos-(6-1)], $this->semStack[$stackPos-(6-5)], $this->startAttributeStack[$stackPos-(6-1)] + $this->endAttributes);
            },
            514 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayDimFetch($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            515 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayDimFetch($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            516 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayDimFetch($this->semStack[$stackPos-(4-1)], $this->s