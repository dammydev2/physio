<?php
namespace Hamcrest\Arrays;

/**
 * Tests for the presence of both a key and value inside an array.
 */
use Hamcrest\Description;
use Hamcrest\Matcher;
use Hamcrest\TypeSafeMatcher;
use Hamcrest\Util;

/**
 * @namespace
 */

class IsArrayContainingKeyValuePair extends TypeSafeMatcher
{

    private $_keyMatcher;
    private $_valueMatcher;

    public function __construct(Matcher $keyMatcher, Matcher $valueMatcher)
    {
        parent::__construct(self::TYPE_ARRAY);

        $this->_keyMatcher = $keyMatcher;
        $this->_valueMatcher = $valueMatcher;
    }

    protected function matchesSafely($array)
    {
        foreach ($array as $key => $value) {
            if ($this->_keyMatcher->matches($key) && $this->_valueMatcher->matches($value)