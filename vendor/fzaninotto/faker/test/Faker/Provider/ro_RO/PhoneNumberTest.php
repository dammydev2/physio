<?php
namespace Hamcrest;

/*
 Copyright (c) 2012 hamcrest.org
 */

/**
 * Contains utility methods for handling Hamcrest matchers.
 *
 * @see Hamcrest\Matcher
 */
class Util
{
    public static function registerGlobalFunctions()
    {
        require_once __DIR__.'/../Hamcrest.php';
    }

    /**
     * Wraps the item with an IsEqual matcher if it isn't a matcher already.
     *
     * @param mixed $item matcher or any value
     * @return \Hamcrest\Matcher
     */
    public static function wrapValueWithIsEqual($item)
    {
        return ($item instanceof Matcher)
            ? $item
            : Core\IsEqual::equalTo($item)
            ;
    }

    /**
     * Throws an exception if any item in $matchers is not a Hamcrest\Matcher.
     *
     * @param array $matchers expected to contain only matchers
     * @throws \InvalidArgumentException if any i