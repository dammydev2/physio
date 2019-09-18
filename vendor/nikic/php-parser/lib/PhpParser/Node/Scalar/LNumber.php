,    4,    4,    4,    1,    4,
            0,    1,    1,    3,    1,    1,    4,    3,    1,    1,
            1,    0,    0,    2,    3,    1,    3,    1,    4,    2,
            2,    2,    1,    2,    1,    1,    1,    4,    3,    3,
            3,    6,    3,    1,    1,    1
    );

    protected function initReduceCallbacks() {
        $this->reduceCallbacks = [
            0 => function ($stackPos) {
                $this->semValue = $this->semStack[$stackPos];
            },
            1 => function ($stackPos) {
                 $this->semValue = $this->handleNamespaces($this->semStack[$stackPos-(1-1)]);
            },
            2 => function ($stackPos) {
                 if (is_array($this->semStack[$stackPos-(2-2)])) { $this->semValue = array_merge($this->semStack[$stackPos-(2-1)], $this->semStack[$stackPos-(2-2)]); } else { $this->semStack[$stackPos-(2-1)][] = $this->semStack[$stackPos-(2-2)]; $this->semValue = $this->semStack[$stackPos-(2-1)]; };
            },
            3 => function ($stackPos) {
                 $this->semValue = array();
            },
            4 => function ($stackPos) {
                 $startAttributes = $this->lookaheadStartAttributes; if (isset($startAttributes['comments'])) { $nop = new Stmt\Nop($startAttributes + $this->endAttributes); } else { $nop = null; };
            if ($nop !== null) { $this->semStack[$stackPos-(1-1)][] = $nop; } $this->semValue = $this->semStack[$stackPos-(1-1)];
            },
            5 => function ($stackPos) {
                $this->semValue = $this->semStack[$stackPos];
            },
            6 => function ($stackPos) {
                $this->semValue = $this->semStack[$stackPos];
            },
            7 => function ($stackPos) {
                $this->semValue = $this->semStack[$stackPos];
            },
            8 => function ($stackPos) {
                $this->semValue = $this->semStack[$stackPos];
            },
            9 => function ($stackPos) {
                $this->semValue = $this->semStack[$stackPos];
            },
            10 => function ($stackPos) {
                $this->semValue = $this->semStack[$stackPos];
            },
            11 => function ($stackPos) {