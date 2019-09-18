<?php
namespace Hamcrest\Arrays;

/*
 Copyright (c) 2009 hamcrest.org
 */

// NOTE: This class is not exactly a direct port of Java's since Java handles
//       arrays quite differently than PHP

// TODO: Allow this to take matchers or values within the array
use Hamcrest\Description;
use Hamcrest\TypeSafeMatcher;
use Hamcrest\Util;

/**
 * Matcher for array whose elements satisfy a sequence of matchers.
 * The array size must equal the number of element matchers.
 */
class IsArray extends TypeSafeMatcher
{

    private $_elementMatchers;

    public function __construct(array $elementMatchers)
    {
        parent::__construct(self::TYPE_ARRAY);

        Util::checkAllAreMatchers($elementMatchers);

        $this->_elementMatchers = $elementMatchers;
    }

    protected function matchesSafely($array)
    {
        if (array_keys($array) != array_keys($this->_elementMatchers)) {
            return false;
        }

        /