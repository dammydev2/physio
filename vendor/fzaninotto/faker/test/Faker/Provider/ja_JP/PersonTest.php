es to TRUE for a collection in which every item matches $itemMatcher
     */
    function everyItem(\Hamcrest\Matcher $itemMatcher)
    {
        return \Hamcrest\Core\Every::everyItem($itemMatcher);
    }
}

if (!function_exists('hasToString')) {    /**
     * Does array size satisfy a given matcher?
     */
    function hasToString($matcher)
    {
        return \Hamcrest\Core\HasToString::hasToString($matcher);
    }
}

if (!function_exists('is')) {    /**
     * Decorates another Matcher, retaining the behavior but allowing tests
     * to be slightly more expressive.
     *
     * For example:  assertThat($cheese, equalTo($smelly))
     *          vs.  assertThat($cheese, is(equalTo($smelly)))
     */
    function is($value)
    {
        return \Hamcrest\Core\Is::is($value);
    }
}

if (!function_exists('anything')) {    /**
     * This matcher always evaluates to true.
     *
     * @param string $description A meaningful string used when describing itself.
     *
     * @return \Hamcrest\Core\IsAnything
     */
    function anything($description = 'ANYTHING')
    {
        return \Hamcrest\Core\IsAnything::anything($description);
    }
}

if (!function_exists('hasItem')) {    /**
     * Test if the value is an array containing this matcher.
     *
     * Example:
     * <pre>
     * assertThat(array('a', 'b'), hasItem(equalTo('b')));
     * //Convenience defaults to equ