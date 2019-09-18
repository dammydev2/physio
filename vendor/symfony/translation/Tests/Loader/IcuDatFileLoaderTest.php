bute(\NumberFormatter::PUBLIC_RULESETS),
                ]
            ),
            Caster::PREFIX_VIRTUAL.'symbols' => new EnumStub(
                [
                    'DECIMAL_SEPARATOR_SYMBOL' => $c->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL),
                    'GROUPING_SEPARATOR_SYMBOL' => $c->getSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL),
                    'PATTERN_SEPARATOR_SYMBOL' => $c->getSymbol(\NumberFormatter::PATTERN_SEPARATOR_SYMBOL),
                    'PERCENT_SYMBOL' => $c->getSymbol(\NumberFormatter::PERCENT_SYMBOL),
                    'ZERO_DIGIT_SYMBOL' => $c->getSymbol(\NumberFormatter::ZERO_DIGIT_SYMBOL),
                    'DIGIT_SYMBOL' => $c->getSymbol(\NumberFormatter::DIGIT_SYMBOL),
                    'MINUS_SIGN_SYMBOL' => $c->getSymbol(\NumberFormatter::MINUS_SIGN_SYMBOL),
                    'PLUS_SIGN_SYMBOL' => $c->getSymbol(\NumberFormatter::PLUS_SIGN_SYMBOL),
                    'CURRENCY_SYMBOL' => $c->getSymbol(\NumberFormatter::CURRENCY_SYMBOL),
                    'INTL_CURRENCY_SYMBOL' => $c->getSymbol(\NumberFormatter::INTL_CURRENCY_SYMBOL),
                    'MONETARY_SEPARATOR_SYMBOL' => $c->getSymbol(\NumberFormatter::MONETARY_SEPARATOR_SYMBOL),
                    'EXPONENTIAL_SYMBOL' => $c->getSymbol(\NumberFormatter::EXPONENTIAL_SYMBOL),
                    'PERMILL_SYMBOL' => $c->getSymbol(\NumberFormatter::PERMILL_SYMBOL),
                    'PAD_ESCAPE_SYMBOL' => $c->getSymbol(\NumberFormatter::PAD_ESCAPE_SYMBOL),
                    'INFINITY_SYMBOL' => $c->getSymbol(\NumberFormatter::INFINITY_SYMBOL),
                    'NAN_SYMBOL' => $c->getSymbol(\NumberFormatter::NAN_SYMBOL),
                    'SIGNIFICANT_DIGIT_SYMBOL' => $c->getSymbol(\NumberFormatter::SIGNIFICANT_DIGIT_SYMBOL),
                    'MONETARY_GROUPING_SEPARATOR_SYMBOL' => $c->getSymbol(\NumberFormatter::MONETARY_GROUPING_SEPARATOR_SYMBOL),
                ]
             ),
        ];

        return self::castError($c, $a);
    }

    public static function castIntlTimeZone(\IntlTimeZone $c, array $a, Stub $stub, $isNested)
    {
        $a += [
            Caster::PREFIX_VIRTUAL.'display_name' => $c->getDisplayName(),
            Caster::PREFIX_VIRTUAL.'id' => $c->getID(),
            Caster::PREFIX_VIRTUAL.'raw_offset' => $c->getRawOffset(),
        ];

        if ($c->useDayli