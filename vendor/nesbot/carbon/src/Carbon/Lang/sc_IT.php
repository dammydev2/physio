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
}, 'tlh');

/*
 * Authors:
 * - François B
 * - Serhan Apaydın
 * - Dominika
 */
return [
    'year' => 'wa’ DIS|:count DIS',
    'month' => 'wa’ jar|:count jar',
    'week' => 'wa’ hogh|:count hogh',
    'day' => 'wa’ jaj|:count jaj',
    'hour' => 'wa’ rep|:count rep',
    'minute' => 'wa’ tup|:count tup',
    'second' => 'puS lup|:count lup',
    'ago' => function ($time) {
        $output = strtr($time, [
            'jaj' => 'Hu’',
            'jar' => 'wen',
            'DIS' => 'ben',
        ]);

        return $output === $time ? "$time ret" : $output;
    },
    'from_now' => function ($time) {
        $output = strtr($time, [
            'jaj' => 'leS',
            'jar' => 'waQ',
            'DIS' => 'nem',
        ]);

        return $output === $time ? "$time pIq" : $output;
    },
    'diff_yesterday' => 'wa’Hu’',
    'diff_tomorrow' => 'wa’leS',
    'formats' => [
        'LT' => 'HH:mm',
        'LTS' => 'HH:mm:ss',
        'L' => 'DD.MM.YYYY',
        'LL' => 'D MMMM YYYY',
        'LLL' => 'D MMMM YYYY HH:mm',
        'LLLL' => 'dddd, D MMMM YYYY HH:mm',
    ],
    'calendar' => [
        'sameDay' => '[DaHjaj] LT',
        'nextDay' => '[wa’leS] LT',
        'nextWeek' => 'LLL',
        'lastDay' => '[wa’Hu’] LT',
        'lastWeek' => 'LLL',
        'sameElse' => 'L',
    ],
    'ordinal' => ':number.',
    'months' => ['tera’ jar wa’', 'tera