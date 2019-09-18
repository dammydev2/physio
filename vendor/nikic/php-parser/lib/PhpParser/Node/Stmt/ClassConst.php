                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            160 => function ($stackPos) {
                 $startAttributes = $this->startAttributeStack[$stackPos-(1-1)]; if (isset($startAttributes['comments'])) { $this->semValue = new Stmt\Nop($startAttributes + $this->endAttributes); } else { $this->semValue = null; };
            if ($this->semValue === null) $this->semValue = array(); /* means: no statement */
            },
            161 => function ($stackPos) {
                 $this->semValue = array();
            },
            162 => function ($stackPos) {
                 $this->semStack[$stackPos-(2-1)][] = $this->semStack[$stackPos-(2-2)]; $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            163 => function ($stackPos) {
                 $this->semValue = new Stmt\Catch_(array($this->semStack[$stackPos-(8-3)]), $this->semStack[$stackPos-(8-4)], $this->semStack[$stackPos-(8-7)], $this->startAttributeStack[$stackPos-(8-1)] + $this->endAttributes);
            },
            164 => function ($stackPos) {
                 $this->semValue = null;
            },
            165 => function ($stackPos) {
                 $this->semValue = new Stmt\Finally_($this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            166 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            167 => function ($stackPos) {