  Expr\Assign::class, Expr\AssignRef::class, AssignOp\Plus::class, AssignOp\Minus::class,
            AssignOp\Mul::class, AssignOp\Div::class, AssignOp\Concat::class, AssignOp\Mod::class,
            AssignOp\BitwiseAnd::class, AssignOp\BitwiseOr::class, AssignOp\BitwiseXor::class,
            AssignOp\ShiftLeft::class, AssignOp\ShiftRight::class, AssignOp\Pow::class, AssignOp\Coalesce::class
        ];
        foreach ($assignOps as $assignOp) {
            $this->fixupMap[$assignOp] = [
                'var' => self::FIXUP_PREC_LEFT,
                'expr' => self::FIXUP_PREC_RIGHT,
            ];
        }

        $prefixOps = [
            Expr\BitwiseNot::class, Expr\BooleanNot::class, Expr\UnaryPlus::class, Expr\UnaryMinus::class,
            Cast\Int_::class, Cast\Double::class, Cast\String_::class, Cast\Array_::class,
            Cast\Object_::class, Cast\Bool_::class, Cast\Unset_::class, Expr\ErrorSuppress::class,
            Expr\YieldFrom::class, Expr\Print_::class, Expr\Include_::class,
        ];
        foreach ($prefixOps as $prefixOp) {
            $this->fixupMap[$prefixOp] = ['expr' => self::FIXUP_PREC_RIGHT];
        }
    }

    /**
     * Lazily initializes the removal map.
     *
     * The removal map is used to determine which additional tokens should be retur