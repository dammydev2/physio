(1-1)];
            },
            127 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            128 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            129 => function ($stackPos) {
                 throw new Error('__HALT_COMPILER() can only be used from the outermost scope', $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            130 => function ($stackPos) {

        if ($this->semStack[$stackPos-(3-2)]) {
            $this->semValue = $this->semStack[$stackPos-(3-2)]; $attrs = $this->startAttributeStack[$stackPos-(3-1)]; $stmts = $this->semValue; if (!empty($attrs['comments'])) {$stmts[0]->setAttribute('comments', array_merge($attrs['comments'], $stmts[0]->getAttribute