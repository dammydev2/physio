<?php

/*
 Copyright (c) 2009-2010 hamcrest.org
 */

// This file is generated from the static method @factory doctags.

namespace Hamcrest;

/**
 * A series of static factories for all hamcrest matchers.
 */
class Matchers
{

    /**
     * Evaluates to true only if each $matcher[$i] is satisfied by $array[$i].
     */
    public static function anArray(/* args... */)
    {
        $args = func_get_args();
        return call_user_func_array(array('\Hamcrest\Arrays\IsArray', 'anArray'), $args);
    }

    /**
     * Evaluates to true if any item in an array satisfies the given matcher.
     *
     * @param mixed $item as a {@link Hamcrest\Matcher} or a value.
     *
     * @return \Hamcrest\Arrays\IsArrayContaining
     */
    public static 