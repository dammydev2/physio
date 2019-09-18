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
}, 'tzm');

/*
 * Authors:
 * - Josh Soref
 * - JD Isaacks
 */
return [
    'year' => 'ⴰⵙⴳⴰⵙ|:count ⵉⵙⴳⴰⵙⵏ',
    'month' => 'ⴰⵢoⵓⵔ|:count ⵉⵢⵢⵉⵔⵏ',
    'week' => ':count ⵉⵎⴰⵍⴰⵙⵙ',
    'day' => 'ⴰⵙⵙ|:count oⵙⵙⴰⵏ',
    'hour' => 'ⵙⴰⵄⴰ|:count ⵜⴰⵙⵙⴰⵄⵉⵏ',
    'minute' => 'ⵎⵉⵏⵓⴺ|:count ⵎⵉⵏⵓⴺ',
    'second' => 'ⵉⵎⵉⴽ|:count ⵉⵎⵉⴽ',
    'ago' => 'ⵢⴰⵏ :time',
    'from_now' => 'ⴷⴰⴷⵅ ⵙ ⵢⴰⵏ :time',
    'formats' => [
        'LT' => 'HH:mm',
        'LTS' => 'HH:mm:ss',
        'L' => 'DD/MM/YYYY',
        'LL' => 'D MMMM YYYY',
        'LLL' => 'D MMMM YYYY HH:mm',
        'LLLL' => 'dddd D MMMM YYYY HH:mm',
    ],
    'calendar' => [
        'sameDay' => '[ⴰⵙⴷⵅ ⴴ] LT',
        'nextDay' => '[ⴰⵙⴽⴰ ⴴ] LT',
        'nextWeek' => 'dddd [ⴴ] LT',
        'lastDay' => '[ⴰⵚⴰⵏⵜ ⴴ] LT',
        'lastWeek' => 'dddd [ⴴ] LT',
        'sameElse' => 'L',
    ],
    'months' => ['ⵉⵏⵏⴰⵢⵔ', 'ⴱⵕⴰⵢⵕ', 'ⵎⴰⵕⵚ', 'ⵉⴱⵔⵉⵔ', 'ⵎⴰⵢⵢⵓ', 'ⵢⵓⵏⵢⵓ', 'ⵢⵓⵍⵢⵓⵣ', 'ⵖⵓⵛⵜ', 'ⵛⵓⵜⴰⵏⴱⵉⵔ', 'ⴽⵟⵓⴱⵕ', 'ⵏⵓⵡⴰⵏⴱⵉⵔ', 'ⴷⵓⵊⵏⴱⵉⵔ'],
    'months_short' => ['ⵉⵏⵏⴰⵢⵔ', 'ⴱⵕⴰⵢⵕ', 'ⵎⴰⵕ�