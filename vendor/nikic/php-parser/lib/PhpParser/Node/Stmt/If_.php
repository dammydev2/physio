  491 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayItem($this->semStack[$stackPos-(1-1)], null, false, $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            492 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            493 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            494 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            495 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            496 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayDimFetch($this->semStack[$stackPos-(6-2)], $this->semStack[$stackPos-(6-5)], $this->startAttributeStack[$stackPos-(6-1)] + $this->endAttributes);
            },
            497 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayDimFetch($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            498 => function ($stackPos) {
                 $this->semValue = new Expr\Propert