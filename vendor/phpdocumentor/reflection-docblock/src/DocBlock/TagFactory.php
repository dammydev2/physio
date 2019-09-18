<?php

/*
 * Copyright 2012 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace PhpOption;

use IteratorAggregate;

/**
 * Base Option Class.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
abstract class Option implements IteratorAggregate
{
    /**
     * Creates an option given a return value.
     *
     * This is intended for consuming existing APIs and allows you to easily
     * convert them to an option. By default, we treat ``null`` as the None case,
     * and everything else as Some.
     *
     * @param mixed $value The actual return value.
     * @param mixed $noneValue The value which should be considered "None"; null
     *                         by default.
     *
     * @return Option
     */
    public static function fromValue($value, $noneValue = null)
    {
        if ($value === $noneValue) {
            return None::create();
        }

        return new Some($value);
    }

    /**
     * Creates an option from an array's value.
     *
     * If the key does not exist in the array, the array is not actually an array, or the
     * array's value at the given key is null, None is returned.
     *
     * Otherwise, Some is returned wrapping the value at the given key.
     *
     * @param mixed $array a potential array value
     * @param string $key the key to check
     *
     * @return Option
     */
    public static function fromArraysValue($array, $key)
    {
        if ( ! isset($array[$key])) {
            return None::create();
        }

        return new Some($array[$key]);
    }

    /**
     * Creates a lazy-option with the given callback.
     *
     * This is also a helper constructor for lazy-consuming existing APIs where
     * the return value is not yet an option. By default, we treat ``null`` as
     * None case, and everything else as Some.
     *
     * @param callable $callback The callback to evaluate.
     * @param array $arguments
     * @param mixed $noneValue The value which should be considered "None"; null
     *                         by default.
     *
     * @return Option
     */
    public static function fromReturn($callback, array $arguments = array(), $noneValue = null)
    {
        return new LazyOption(function() use ($callback, $arguments, $noneValue) {
            $return = call_user_func_array($callback, $arguments);

            if ($return === $noneValue) {
                return None::create();
            }

            return new Some($return);
        });
    }

    /**
     * Option factory, which creates new option based on passed value.
     * If value is already an option, it simply returns
     * If value is a \Closure, LazyOption with passed callback created and returned. If Option returned from callback,
     * it returns directly (flatMap-like behaviour)
     * On other case value passed to Option::fromValue() method
     *
     * @param Option|\Closure|mixed $value
     * @param null $noneValue used when $value is mixed or Closure, for None-check
     *
     * @return Option
     */
    public static function ensure($value, $noneValue = null)
    {
        if ($value instanceof Option) {
            return $value;
        } elseif ($value instanceof \Closure) {
            return new LazyOption(function() use ($value, $noneValue) {
