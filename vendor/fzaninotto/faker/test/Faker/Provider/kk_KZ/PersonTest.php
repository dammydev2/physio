alue an object?
     */
    function anObject()
    {
        return \Hamcrest\Type\IsObject::objectValue();
    }
}

if (!function_exists('resourceValue')) {    /**
     * Is the value a resource?
     */
    function resourceValue()
    {
        return \Hamcrest\Type\IsResource::resourceValue();
    }
}

if (!function_exists('scalarValue')) {    /**
     * Is the value a scalar (boolean, integer, double, or string)?
     */
    function scalarValue()
    {
        return \Hamcrest\Type\IsScalar::scalarValue();
    }
}

if (!function_exists('stringValue')) {    /**
     * Is the value a string?
     */
    function stringValue()
    {
        return \Hamcrest\Type\IsString::stringValue();
    }
}

if (!function_exists('hasXPath')) {    /**
     * Wraps <code>$matcher</code> with {@link Hamcrest\Core\IsEqual)
     * if it's no