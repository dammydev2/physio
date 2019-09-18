ckPos];
            },
            79 => function ($stackPos) {
                $this->semValue = $this->semStack[$stackPos];
            },
            80 => function ($stackPos) {
                 $this->semValue = new Node\Identifier($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            81 => function ($stackPos) {
                 $this->semValue = new Node\Identifier($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            82 => function ($stackPos) {
                 $this->semValue = new Node\Identifier($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            83 => function ($stackPos) {
                 $this->semValue = new Node\Identifier($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            84 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            85 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            86 => function ($stackPos) {
                 $this->semValue = new Name($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            87 => function ($stackPos) {
                 $this->semValue = new Expr\Variable(substr($this->semStack[$stackPos-(1-1)], 1), $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            88 => function ($stackPos) {
                 /* nothing */
            },
            89 => function ($stackPos) {
                 /* nothing */
            },
            90 => function ($stackPos) {
                 /* nothing */
            },
            91 => function ($stackPos) {
                 $this->emitError(new Error('A trailing comma is not allowed here', $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes));
            },
            92 => function ($stackPos) {
                $this->semValue = $this->semStack[$stackPos];
            },
            93 => function ($stackPos) {
                $this->semValue = $this->semStack[$stackPos];
            },
            94 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            95 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            96 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            97 => function ($stackPos) {
                 $this->semValue = new Stmt\HaltCompiler($this->lexer->handleHaltCompiler(), $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            98 => function ($stackPos) {
                 $this->semValue = new Stmt\Namespace_($this->semStack[$stackPos-(3-2)], null, $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            $this->semValue->setAttribute('kind', Stmt\Namespace_::KIND_SEMICOLON);
            $this->checkNamespace($this->semValue);
            },
            99 => function ($stackPos) {
                 $this->semValue = new Stmt\Namespace_($this->semStack[$stackPos-(5-2)], $this->semStack[$stackPos-(5-4)], $this->startAttributeStack[$stackPos-(5-1)] + $this->endAttributes);
            $this->semValue->setAttribute('kind', Stmt\Namespace_::KIND_BRACED);
            $this->checkNamespace($this->semValue);
            },
            100 => function ($stackPos) {
                 $this->semValue = new Stmt\Namespace_(null, $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            $this->semValue->setAttribute('kind', Stmt\Namespace_::KIND_BRACED);
            $this->checkNamespace($this->semValue);
            },
            101 => function ($stackPos) {
                 $this->semValue = new Stmt\Use_($this->semStack[$stackPos-(3-2)], Stmt\Use_::TYPE_NORMAL, $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            102 => function ($stackPos) {
                 $this->semValue = new Stmt\Use_($this->semStack[$stackPos-(4-3)], $this->semStack[$stackPos-(4-2)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            103 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            104 => function ($stackPos) {
                 $this->semValue = new Stmt\Const_($this->semStack[$stackPos-(3-2)], $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes);
            },
            105 => function ($stackPos) {
                 $this->semValue = Stmt\Use_::TYPE_FUNCTION;
            },
            106 => function ($stackPos) {
                 $this->semValue = Stmt\Use_::TYPE_CONSTANT;
            },
            107 => function ($stackPos) {
                 $this->semValue = new Stmt\GroupUse(new Name($this->semStack[$stackPos-(7-3)], $this->startAttributeStack[$stackPos-(7-3)] + $this->endAttributeStack[$stackPos-(7-3)]), $this->semStack[$stackPos-(7-6)], $this->semStack[$stackPos-(7-2)], $this->startAttributeStack[$stackPos-(7-1)] + $this->endAttributes);
            },
            108 => function ($stackPos) {
                 $this->semValue = new Stmt\GroupUse(new Name($this->semStack[$stackPos-(8-4)], $this->startAttributeStack[$stackPos-(8-4)] + $this->endAttributeStack[$stackPos-(8-4)]), $this->semStack[$stackPos-(8-7)], $this->semStack[$stackPos-(8-2)], $this->startAttributeStack[$stackPos-(8-1)] + $this->endAttributes);
            },
            109 => function ($stackPos) {
                 $this->semValue = new Stmt\GroupUse(new Name($this->semStack[$stackPos-(6-2)], $this->startAttributeStack[$stackPos-(6-2)] + $this->endAttributeStack[$stackPos-(6-2)]), $this->semStack[$stackPos-(6-5)], Stmt\Use_::TYPE_UNKNOWN, $this->startAttributeStack[$stackPos-(6-1)] + $this->endAttributes);
            },
            110 => function ($stackPos) {
                 $this->semValue = new Stmt\GroupUse(new Name($this->semStack[$stackPos-(7-3)], $this->startAttributeStack[$stackPos-(7-3)] + $this->endAttributeStack[$stackPos-(7-3)]), $this->semStack[$stackPos-(7-6)], Stmt\Use_::TYPE_UNKNOWN, $this->startAttributeStack[$stackPos-(7-1)] + $this->endAttributes);
            },
            111 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            112 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            113 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            114 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            115 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            116 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            117 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(2-1)];
            },
            118 => function ($stackPos) {
                 $this->semStack[$stackPos-(3-1)][] = $this->semStack[$stackPos-(3-3)]; $this->semValue = $this->semStack[$stackPos-(3-1)];
            },
            119 => function ($stackPos) {
                 $this->semValue = array($this->semStack[$stackPos-(1-1)]);
            },
            120 => function ($stackPos) {
                 $this->semValue = new Stmt\UseUse($this->semStack[$stackPos-(1-1)], null, Stmt\Use_::TYPE_UNKNOWN, $this->startA