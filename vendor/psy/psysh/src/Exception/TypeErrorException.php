<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Util;

use Psy\Exception\RuntimeException;
use Psy\Reflection\ReflectionClassConstant;
use Psy\Reflection\ReflectionConstant_;

/**
 * A utility class for getting Reflectors.
 */
class Mirror
{
    const CONSTANT        = 1;
    const METHOD          = 2;
    const STATIC_PROPERTY = 4;
    const PROPERTY        = 8;

    /**
     * Get a Reflector for a function, class or instance, constant, method or property.
     *
     * Optionally, pass a $filter param to restrict the types of members checked. For example, to only Reflectors for
     * static properties and constants, pass:
     *
     *    $filter = Mirror::CONSTANT | Mirror::STATIC_PROPERTY
     *
     * @throws \Psy\Exception\RuntimeException when a $member specified but not present on $value
     * @throws \InvalidArgumentException       if $value is something other than an object or class/function name
     *
     * @param mixed  $value  Class or function name, or variable instance
     * @param string $member Optional: property, cons