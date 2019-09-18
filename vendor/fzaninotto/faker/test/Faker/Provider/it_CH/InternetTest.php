<?php

/*
 Copyright (c) 2009-2010 hamcrest.org
 */

// This file is generated from the static method @factory doctags.

if (!function_exists('assertThat')) {
    /**
     * Make an assertion and throw {@link Hamcrest_AssertionError} if it fails.
     *
     * Example:
     * <pre>
     * //With an identifier
     * assertThat("assertion identifier", $apple->flavour(), equalTo("tasty"));
     * //Without an identifier
     * assertThat($apple->flavour(), equalTo("tasty"));
     * //Evaluating a boolean expression
     * assertThat("some error", $a > $b);
     * </pre>
     */
    function assertThat()
    {
        $args = func_get_args();
        call_user_func_array(
            array('Hamcrest\MatcherAssert', 'assertThat'),
            $arg