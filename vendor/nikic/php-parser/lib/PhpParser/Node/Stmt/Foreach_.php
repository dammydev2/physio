startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            383 => function ($stackPos) {
                 $attrs = $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes; $attrs['kind'] = Expr\Array_::KIND_LONG;
            $this->semValue = new Expr\Array_($this->semStack[$stackPos-(4-3)], $attrs);
            },
            384 => function ($stackPos) {
                 $attrs = $this->startAttributeStack[$stackPos-(3-1)] + $this->endAttributes; $attrs['kind'] = Expr\Array_::KIND_SHORT;
            $this->semValue = new Expr\Array_($this->semStack[$stackPos-(3-2)], $attrs);
            },
            385 => function ($stackPos) {
                 $this->semValue = new Expr\ArrayDimFetch($this->semStack[$stackPos-(4-1)], $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            386 => function ($stackPos) {
                 $attrs = $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes; $attrs['kind'] = ($this->semStack[$stackPos-(4-1)][0] === "'" || ($this->semStack[$stackPos-(4-1)][1] === "'" && ($this->semStack[$stackPos-(4-1)][0] === 'b' || $this->semStack[$stackPos-(4-1)][0] === 'B')) ? Scalar\String_::KIND_SINGLE_QUOTED : Scalar\String_::KIND_DOUBLE_QUOTED);
            $this->semValue = new Expr\ArrayDimFetch(new Scalar\String_(Scalar\String_::parse($this->semStack[$stackPos-(4-1)]), $attrs), $this->semStack[$stackPos-(4-3)], $this->startAttributeStack[$stackPos-(4-1)] + $this->endAttributes);
            },
            387 => function ($stackPos) {
                 