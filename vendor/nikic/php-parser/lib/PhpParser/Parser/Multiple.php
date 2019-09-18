2)]; $attrs = $this->startAttributeStack[$stackPos-(3-1)]; $stmts = $this->semValue; if (!empty($attrs['comments'])) {$stmts[0]->setAttribute('comments', array_merge($attrs['comments'], $stmts[0]->getAttribute('comments', []))); };
        } else {
            $startAttributes = $this->startAttributeStack[$stackPos-(3-1)]; if (isset($startAttributes['comments'])) { $this->semValue = new Stmt\Nop($startAttributes + $this->endAttributes); } else { $this->semValue = null; };
            if (null === $this->semValue) { $this->semValue = array(); }
        }

            },
            142 => function ($stackPos) {
                 $this->semValue = new Stmt\If_($this->semStack[$stackPos-(7-3)], ['stmts' => is_array($this->semStack[$stackPos-(7-5)]) ? $this->semStack[$stackPos-(7-5)] : array($this->semStack[$stackPos-(7-5)]), 'elseifs' => $this->semStack[$stackPos-(7-6)], 'else' => $this->semStack[$stackPos-(7-7)]], $this->startAttributeStack[$stackPos-(7-1)] + $this->endAttributes);
            },
            143 => function ($stackPos) {
                 $this->semValue = new Stmt\If_($this->semStack[$stackPos-(10-3)], ['stmts' => $this->semStack[$stackPos-(10-6)], 'elseifs' => $this->semStack[$stackPos-(10-7)], 'else' => $this->semStack[$stackPos-(10-8)]], $this->startAttributeStack[$stackPos-(10-1)] + $this->endAttributes);
            },
            144 => function ($stackPos) {
                 $this->semValue = new Stmt\While_($this->semStack[$stackPos-(5-3)], $this->semStack[$stackPos-(5-5)], $this->startAttributeStack[$stackPos-(5-1)] + $this->endAttributes);
            },
            145 => function ($stackPos) {
     