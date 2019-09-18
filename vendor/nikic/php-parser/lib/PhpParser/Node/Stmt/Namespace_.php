          535 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            536 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayItem($this->semStack[$stackPos-(3-3)], $this->semStack[$stackPos-(3-1)], false, $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            537 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayItem($this->semStack[$stackPos-(1-1)], null, false, $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            538 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayItem($this->semStack[$stackPos-(4-4)], $this->semStack[$stackPos-(4-1)], true, $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            539 => function ($stackPos) {
                 $this->semValu