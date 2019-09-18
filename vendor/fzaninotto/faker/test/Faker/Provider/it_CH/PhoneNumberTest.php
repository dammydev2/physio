y with each other.
     */
    function hasKeyValuePair($key, $value)
    {
        return \Hamcrest\Arrays\IsArrayContainingKeyValuePair::hasKeyValuePair($key, $value);
    }
}

if (!function_exists('hasEntry')) {    /**
     * Test if an array has both an key and value in parity with each other.
     */
    function hasEntry($key, $value)
    {
        return \Hamcrest\Arrays\IsArrayContainingKeyValuePair::hasKeyValuePair($key, $value);
    }
}

if (!function_exists('arrayWithSize')) {    /**
     * Does array size satisfy a given matcher?
     *
     * @param \Hamcrest\Matcher|int $size as a {@link Hamcrest\Matcher} or a value.
     *
     * @return \Hamcrest\Arrays\IsArrayWithSize
     */
    function arrayWithSize($size)
   