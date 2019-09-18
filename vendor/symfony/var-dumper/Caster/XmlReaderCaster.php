getSymbol(\NumberFormatter::MINUS_SIGN_SYMBOL);
        $expectedSymbol8 = $var->getSymbol(\NumberFormatter::PLUS_SIGN_SYMBOL);
        $expectedSymbol9 = $var->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
        $expectedSymbol10 = $var->getSymbol(\NumberFormatter::INTL_CURRENCY_SYMBOL);
        $expectedSymbol11 = $var->getSymbol(\NumberFormatter::MONETARY_SEPARATOR_SYMBOL);
        $expectedSymbol12 = $var->getSymbol(\NumberFormatter::EXPONENTIAL_SYMBOL);
        $expectedSymbol13 = $var->getSymbol(\NumberFormatter::PERMILL_SYMBOL);
        $expectedSymbol14 = $var->getSymbol(\NumberFormatter::PAD_ESCAPE_SYMBOL);
        $expectedSymbol15 = $var->getSymbol(\NumberFormatter::INFINITY_SYMBOL);
        $expectedSymbol16 = $var->getSymbol(\NumberFormatter::NAN_SYMBOL);
        $expectedSymbol17 = $var->getSymbol(\NumberFormatter::SIGNIFICANT_DIGIT_SYMBOL);
        $expectedSymbol18 = $var->getSymbol(\NumberFormatter::MONETARY_GROUPING_SEPARATOR_SYMBOL);

        $expected = <<<EOTXT
NumberFormatter {
  locale: "$expectedLocale"
  pattern: "$expectedPattern"
  attributes: {
    PARSE_INT_ONLY: $expectedAttribute1
    GROUPING_USED: $expectedAttribute2
    DECIMAL_ALWAYS_SHOWN: $expectedAttribute3
    MAX_INTEGER_DIGITS: $expectedAttribute4
    MIN_INTEGER_DIGITS: $expectedAttribute5
    INTEGER_DIGITS: $expectedAttribute6
    MAX_FRACTION_DIGITS: $expectedAttribute7
    MIN_FRACTION_DIGITS: $expectedAttribute8
    FRACTION_DIGITS: $expectedAttribute9
    MULTIPLIER: $expectedAttribute10
    GROUPING_SIZE: $expectedAttribute11
    ROUNDING_MODE: $expectedAttribute12
    ROUNDING_INCREMENT: $expectedAttribute13
    FORMAT_WIDTH: $expectedAttribute14
    PADDING_POSITION: $expectedAttribute15
    SECONDARY_GROUPING_SIZE: $expectedAttribute16
    SIGNIFICANT_DIGITS_USED: $expectedAttribute17
    MIN_SIGNIFICANT_DIGITS: $expectedAttribute18
    MAX_SIGNIFICANT_DIGITS: $expectedAttribute19
    LENIENT_PARSE: $expectedAttribute20
  }
  text_attributes: {
    POSITIVE_PREFIX: "$expectedTextAttribute1"
    POSITIVE_SUFFIX: "$expectedTextAttribute2"
    NEGATIVE_PREFIX: "$expectedTextAttribute3"
    NEGATIVE_SUFFIX: "$expectedTextAttribute4"
    PADDING_CHARACTER: "$expectedTextAttribute5"
    CURRENCY_CODE: "$expectedTextAttribute6"
    DEFAULT_RULESET: $expectedTextAttribute7
    PUBLIC_RULESETS: $expectedTextAttribute8
  }
  symbols: {
    DECIMAL_SEPARATOR_SYMBOL: "$expectedSymbol1"
    GROUPING_SEPARATOR_SYMBOL: "$expectedSymbol2"
    PATTERN_SEPARATOR_SYMBOL: "$expectedSymbol3"
    PERCENT_SYMBOL: "$expectedSymbol4"
    ZERO_DIGIT_SYMBOL: "$expectedSymbol5"
    DIGIT_SYMBOL: "$expectedSymbol6"
    MINUS_SIGN_SYMBOL: "$expectedSymbol7"
    PLUS_SIGN_SYMBOL: "$expectedSymbol8"
    CURRENCY_SYMBOL: "$expectedSymbol9"
    INTL_CURRENCY_SYMBOL: "$expectedSymbol10"
    MONETARY_SEPARATOR_SYMBOL: "$expectedSymbol11"
    EXPONENTIAL_SYMBOL: "$expectedSymbol12"
    PERMILL_SYMBOL: "$expectedSymbol13"
    PAD_ESCAPE_SYMBOL: "$expectedSymbol14"
    I