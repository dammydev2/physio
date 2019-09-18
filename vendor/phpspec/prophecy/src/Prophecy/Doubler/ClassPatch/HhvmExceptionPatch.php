<?php

/*
 * This file is part of the Prophecy.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *     Marcello Duarte <marcello.duarte@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prophecy\Util;

use Prophecy\Call\Call;

/**
 * String utility.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class StringUtil
{
    private $verbose;

    /**
     * @param bool $verbose
     */
    public function __construct($verbose = true)
    {
        $this->verbose = $verbose;
    }

    /**
     * Stringifies any provided value.
     *
     * @param mixed   $value
     * @param boolean $exportObject
     *
     * @return string
     */
    public function stringify($value, $exportObject = true)
    {
        if (is_array($value)) {
            if (range(0, count($value) - 1) === array_keys($value)) {
                return '['.implode(', ', array_map(array($this, __FUNCTION__), $value)).']';
            }

            $stringify = array($this, __FUNCTION__);

            return '['.implode(', ', array_map(function ($item, $key) use ($stringify) {
                return (is_integer($key) ? $key : '"'.$key.'"').
                    ' => '.call_user_func($stringify, $item);
            }, $value, array_keys($value))).']';
        }
        if (is_resource($value)) {
            return get_resource_type($value).':'.$value;
        }
        if (is_object($v