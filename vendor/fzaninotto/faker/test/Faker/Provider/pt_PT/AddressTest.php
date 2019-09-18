Number\OrderingComparison::greaterThan($value);
    }

    /**
     * The value is >= $value.
     */
    public static function greaterThanOrEqualTo($value)
    {
        return \Hamcrest\Number\OrderingComparison::greaterThanOrEqualTo($value);
    }

    /**
     * The value is >= $value.
     */
    public static function atLeast($value)
    {
        return \Hamcrest\Number\OrderingComparison::greaterThanOrEqualTo($value);
    }

    /**
     * The value is < $value.
     */
    public static function lessThan($value)
    {
        return \Hamcrest\Number\OrderingComparison::lessThan($value);
    }

    /**
     * The value is <= $value.
     */
    public static function lessThanOrEqualTo($value)
    {
        return \Hamcrest\Number\OrderingComparison::lessThanOrEqualTo($value);
    }

    /**
     * The value is <= $value.
     */
    public static function atMost($value)
    {
        return \