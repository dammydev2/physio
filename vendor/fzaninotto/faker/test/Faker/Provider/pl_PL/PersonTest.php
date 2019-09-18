c static function emptyArray()
    {
        return \Hamcrest\Arrays\IsArrayWithSize::emptyArray();
    }

    /**
     * Matches an empty array.
     */
    public static function nonEmptyArray()
    {
        return \Hamcrest\Arrays\IsArrayWithSize::nonEmptyArray();
    }

    /**
     * Returns true if traversable is empty.
     */
    public static function emptyTraversable()
    {
        return \Hamcrest\Collection\IsEmptyTraversable::emptyTraversable();
    }

    /**
     * Returns true if traversable is not empty.
     */
    public static function nonEmptyTraversable()
    {
        return \Hamcrest\Collection\IsEmptyTraversable::nonEmptyTraversable();
    }

    /**
     * Does traversable size satisfy a given matcher?
     */
    public static function traversableWithSize($size)
    {
        return \Hamcrest\Collection\IsTraversableWithSize::traversableWithSize($size);
    }

    /**
     * Evaluates to true only if ALL of the passed in matchers evaluate to true.
     */
    public static function allOf(/* args... */)
    {
        $args = func_get_args();
        return call_user_func_array(array('\Hamcrest\Core\AllOf', 'allOf'), $args);
    }

    /**
     * Evaluates to true if ANY of the passed in matchers evaluate to true.
     */
    public static function anyOf(/* args... */)
    {
        $args = func_get_args();
        return call_user_func_array(array('\Hamcrest\Core\AnyOf', 'anyOf'), $args);
    }

    /**
     * Evaluates to false if ANY of the passed in matchers evaluate to true.
     */
    public static function noneOf(/* args... */)
    {
        $args = func_get_args();
        return call_user_func_array(array('\Hamcrest\Core\AnyOf', 'noneOf'), $args);
    }

    /**
     * This is useful for fluently combining matchers that must both pass.
     * For example:
     * <pre>
     *   assertThat($string, both(containsString("a"))->andAlso(containsString("b")));
     * </pre>
     */
    public static function both(\Hamcrest\Matcher $matcher)
    {
        return \Hamcrest\Core\CombinableMatcher::both($matcher);
    }

    /**
     * This is useful for fluently combining matchers where either may pass,
     * for example:
     * <pre>
     *   assertThat($string, either(containsString("a"))->orElse(containsString("b")));
     * </pre>
     */
    public static function either(\Hamcrest\Matcher $matcher)
    {
        return \Hamcrest\Core\CombinableMatcher::either($matcher);
    }

    /**
     * Wraps an existing matcher and overrides the description when it fails.
     */
    public static function describedAs(/* args... */)
    {
        $args = func_get_args();
        return call_user_func_array(array('\Hamcre