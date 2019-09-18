[$stackPos-(2-1)] + $this->endAttributes);
            },
            142 => function ($stackPos) {
                 $this->semValue = new Stmt\Return_($this->semStack[$stackPos-(3-2)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            143 => function ($stackPos) {
                 $this->semValue = new Stmt\Global_($this->semStack[$stackPos-(3-2)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            144 => function ($stackPos) {
                 $this->semValue = new Stmt\Static_($this->semStack[$stackPos-(3-2)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            145 => function ($stackPos) {
                 $this->semValue = new Stmt\Echo_($this->semStack[$stackPos-(3-2)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            146 => function ($stackPos) {
                 $this->semValue = new Stmt\InlineHTML($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->en