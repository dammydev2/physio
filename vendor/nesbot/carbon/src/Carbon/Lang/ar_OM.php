<?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
\Symfony\Component\Translation\PluralizationRules::set(function ($number) {
    return $number === 1 ? 0 : 1;
}, 'cv');

/*
 * Authors:
 * - Josh Soref
 * - François B
 * - JD Isaacks
 */
return [
    'year' => ':count ҫул',
    'a_year' => 'пӗр ҫул|:count ҫул',
    'month' => ':count уйӑх',
    'a_month' => 'пӗр уйӑх|:count уйӑх',
    'week' => ':count эрне',
    'a_week' => 'пӗр эрне|:count эрне',
    'day' => ':count кун',
    'a_day' => 'пӗр кун|:count кун',
    'hour' => ':count сехет',
    'a_hour' => 'пӗр сехет|:count сехет',
    'minute' => ':count минут',
    'a_minute' => 'пӗр минут|:count минут',
    'second' => ':count ҫеккунт',
    'a_second' => 'пӗр-ик ҫеккунт|:count ҫеккунт',
    'ago' => ':time каялла',
    'from_now' => function ($time) {
        return $time.(preg_mat