<?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 * Authors:
 * - Glavić
 * - Milos Sakovic
 */
return [
    'year' => '{2,3,4,22,23,24,32,33,34,42,43,44,52,53,54}:count године|[0,Inf[:count година',
    'y' => ':count г.',
    'month' => '{1}:count мјесец|{2,3,4}:count мјесеца|[0,Inf[:count мјесеци',
    'm' => ':count мј.',
    'week' => '{1}:count недјеља|{2,3,4}:count недјеље|[0,Inf[:count недјеља',
    'w' => ':count нед.',
    'day' => '{1,21,31}:count дан|[0,Inf[:count дана',
    'd' => ':count д.',
    'hour' => '{1,21}:count сат|{2,3,4,22,23,24}:count сата|[0,Inf[:count сати',
    'h' => ':count ч.',
    'minute' => '{1,21,31,41,51}:count минут|[0,Inf[:count минута',
    'min' => ':count мин.',
    'second' => '{1,21,31,41,51}:count секунд|{2,3,4,22,23,24,32,33,34,42,43,44,52,53,54}:count секунде|[0,Inf[:count секунди',
    's' => ':count сек.',
    'ago' => 'прије :time',
    'from_now' => 'за :time',
    'after' => ':time након',
    'before' => ':time прије',

    'year_from_now' => '{1,21,31,41,51}:count годину|{2,3,4,22,23,24,32,33,34,42,43,44,52,53,54}:count године|[0,Inf[:count година',
    'year_ago' => '{1,21,31,41,51}:count годину|{2,3,4,22,23,24,32,33,34,42,43,44,52,53,54}:count године|[0,Inf[:count година',

    'week_from_now' => '{1}:count недјељу|{2,3,4}:count недјеље|[0,Inf[:count недјеља',
    'week_ago' => '{1}:count недјељу|{2,3,4}:count недјеље|[0,Inf[:count недјеља',

    'diff_now' => 'управо сада',
    'diff_yesterday' => 'јуче',
    'diff_tomorrow' => 'сутра',
    'diff_before_yesterday' => 'прекјуче',
    'diff_after_tomorrow' => 'прекосјутра',
    'formats' => [
        'LT' => 'H:mm',
        'LTS' => 'H:mm:ss',
        'L' => 'DD.MM.YYYY',
        'LL' => 'D. MMMM YYYY',
        'LLL' => 'D. MMMM YYYY H:mm',
        'LLLL' => 'dddd, D. MMMM YYYY H:mm',
    ],
    'calendar' => [
        'sameDay' => '[данас у] LT',
        'nextDay' => '[сутра у] LT',
        'nextWeek' => function (\Carbon\CarbonInterface $date) {
            switch ($date->dayOfWeek) {
                case 0:
                    return '[у недељу у] LT';
                case