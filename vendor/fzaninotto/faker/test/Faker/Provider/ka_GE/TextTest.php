ates a new instance of IsSame.
     *
     * @param mixed $object
     *   The predicate evaluates to true only when the argument is
     *   this object.
     *
     * @return \Hamcrest\Core\IsSame
     */
    function sameInstance($object)
    {
        return \Hamcrest\Core\IsSame::sameInstance($object);
    }
}

if (!function_exists('typeOf')) {    /**
     * Is the value a particular built-in type?
     */
    function typeOf($theType)
    {
        return \Hamcrest\Core\IsTypeOf::typeOf($theType);
    }
}

if (!function_exists('set')) {    /**
     * Matches if value (class, object, or array) has named $property.
     */
    function set($property)
    {
        return \Hamcrest\Core\Set::set($property);
    }
}

if (!function_exists('notSet')) {    /**
     * Matches if value (class, object, or array) does not have named $property.
     */
    function notSet($property)
    {
        return \Hamcrest\Core\Set::notSet($property);
    }
}

if (!function_exists('closeTo')) {    /**
     * Matches if value is a number equal to $value within some range of
     * acceptable error $delta.
     */
    function closeTo($value, $delta)
    {
        return \Hamcrest\Number\IsCloseTo::closeTo($value, $delta);
    }
}

if (!function_exists('comparesEqualTo')) {    /**
     * The value is not > $value, nor < $value.
     */
    function comparesEqualTo($value)
    {
        return \Hamcrest\Number\OrderingComparison::comparesEqualTo($value);
    }
}

if (!function_exists('greaterThan')) {    /**
     * The value is > $value.
     */
    function greaterThan($value)
    {
        return \Hamcrest\Number\OrderingComparison::greaterThan($value);
    }
}

if (!function_exists('greaterThanOrEqualTo')) {    /**
     * The value is >= $value.
     */
    function greaterThanOrEqualTo($value)
    {
        return \Hamcrest\Number\OrderingComparison::greaterThanOrEqualTo($value);
    }
}

if (!function_exists('atLeast')) {    /**
     * The value is >= 