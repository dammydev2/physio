l;
            },
            419 => function ($stackPos) {
                 $this->semValue = null;
            },
            420 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            421 => function ($stackPos) {
                 $this->semValue = array();
            },
            422 => function ($stackPos) {
                 $this->semValue = array(new Scalar\EncapsedStringPart(Scalar\String_::parseEscapeSequences($this->semStack[$stackPos-(1-1)], '`', false), $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes));
            },
            423 => function ($stackPos) {
                 foreach ($this->semStack[$stackPos-(1-1)] as $s) { if ($s instanceof Node\Scalar\EncapsedStringPart) { $s->value = Node\Scalar\String_::parseEscapeSequences($s->value, '`', false); } }; $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            424 => function ($stackPos) {
                 $this->semValue = array();
            },
            425 => function ($stackPos) {
                 $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            426 => function ($stackPos) {
                 $this->semValue = $this->parseLNumber($this->semStack[$stackPos-(1-1)], $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes, true);
            },
            427 => function ($stackPos) {
                 $this->semValue = new Scalar\DNumber(Scalar\DNumber::parse($this->semStack[$stackPos-(1-1)]), $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes);
            },
            428 => function ($stackPos) {
                 $attrs = $this->startAttributeStack[$stackPos-(1-1)] + $this->endAttributes; $attrs['kind'] = ($this->semStack[$stackPos-(1-1)][0] === "'" || ($this->semStack[$stackPos-(1-1)][1] === "'" && ($this->semStack[$stackPos-(1-1)][0] === 'b' || $this->semStack[$stackPos-(1-1)][0] === 'B')) ? Scalar\String_::KIND_SINGLE_QUOTED : Scalar\String_::KIND_DOUBLE_QUOTED);
            $this->semValue = new Scalar\String_(Scalar\String_::parse($this->semStack[$stackPos-(1-1)], false), $attrs);
            },
            429 => function ($stackPos) {
       