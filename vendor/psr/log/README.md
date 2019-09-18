<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy;

/**
 * Helpers for bypassing visibility restrictions, mostly used in code generated
 * by the `sudo` command.
 */
class Sudo
{
    /**
     * Fetch a property of an object, bypassing visibility restrictions.
     *
     * @param object $object
     * @param string $property property name
     *
     * @return mixed Value of $object->property
     */
    public static function fetchProperty($object, $property)
    {
        $refl = new \ReflectionObject($object);
        $prop = $refl->getProperty($property);
        $prop->setAccessible(true);

        return $prop->getValue($object);
    }

    /**
     * Assign the value of a property of an object, bypassing visibility restrictions.
     *
     * @param object $object
     * @param string $property property name
     * @param mixed  $value
     *
     * @return mixed Value of $object->property
     */
    public static function assignProperty($object, $prop