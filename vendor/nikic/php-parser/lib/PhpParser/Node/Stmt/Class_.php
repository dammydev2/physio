os) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            270 => function ($stackPos) {
                 $this->semValue = array(null, $this->semStack[$stackPos-(1-1)]);
            },
            271 => function ($stackPos) {
                 $this->semValue = null;
            },
            272 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(3-2)];
            },
            273 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            274 => function ($stackPos) {
                 $this->semValue = 0;
            },
            275 => function ($stackPos) {
                 $this->semValue = 0;
            },
            276 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            277 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            278 => function ($stackPos) {
                 $this->checkModifier($this->semStack[$stackPos-(2-1)], $this->semStack[$stackPos-(2-2)], $stackPos-(2-2)); $this->semValue = $this->semStack[$stackPos-(2-1)] | $this->semStack[$stackPos-(2-2)];
            },
            279 => function ($stackPos) {
                 $this->semValue = Stmt\Class_::MODIFIER_PUBLIC;
            },
            280 => function ($stackPos) {
                 $this->semValue = Stmt\Class_::MODIFIER_PROTECTED;
            },
            281 => function ($stackPos) {
                 $this->semValue = Stmt\Class_::MODIFIER_PRIVATE;
            },
            282 => function ($stackPos) {
                 $this->semValue = Stmt\Class_::MODIFIER_STATIC;
            },
            283 => function ($stackPos) {
                 $this->semValue = Stmt\Class_::MODIFIER_ABSTRACT;
            },
            284 => function ($stackPos) {
                 $this->semValue = Stmt\Class_::MODIFIER_FINAL;
            },
            285 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            286 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            287 => function ($stackPos) {
                 $this->semValue = new Node\VarLikeIdentifier(substr($this->semStack[$stackPos-(1-1)], 1), $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            288 => function ($stackPos) {
                 $this->semValue = new Stmt\PropertyProperty($this->semStack[$stackPos-(1-1)], null, $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            289 => function ($stackPos) {
                 $this->semValue = new Stmt\PropertyProperty($this->semStack[$stackPos-(3-1)], $this->semStack[$stackPos-(3-3)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            290 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
  