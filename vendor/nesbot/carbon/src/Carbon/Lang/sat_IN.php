<?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Authors:
 * - Kunal Marwaha
 * - Josh Soref
 * - François B
 * - kc
 */
return [
    'year' => ':count సంవత్సరం|:count సంవత్సరాలు',
    'a_year' => 'ఒక సంవత్సరం|:count సంవత్సరాలు',
    'y' => ':count సం.',
    'month' => ':count నెల|:count నెలలు',
    'a_month' => 'ఒక నెల|:count నెలలు',
    'm' => ':count నెల|:count నెల.',
    'week' => ':count వారం|:count వారాలు',
    'a_week' => 'ఒక వారం|:count వారాలు',
    'w' => ':count వార.|:count వారా.',
    'day' => ':count రోజు|:count రోజులు',
    'a_day' => 'ఒక రోజు|:count రోజులు',
    'd' => ':count రోజు|:count రోజు.',
    'hour' => ':count గంట|:count గంటలు',
    'a_hour' => 'ఒక గంట|:count గంటలు',
    'h' => ':count గం.',
    'minute' => ':count నిమిషం|:count నిమిషాలు',
    'a_minute' => 'ఒక నిమిషం|:count నిమిషాలు',
    'min' => ':count నిమి.',
    'second' => ':count సెకను|:count సెకన్లు',
    'a_second' => 'కొన్ని క్షణాలు|:count సెకన్లు',
    's' => ':count సెక.',
    'ago' => ':time క్రితం',
    'from_now' => ':time లో',
    'diff_yesterday' => 'నిన్న',
    'diff_tomorrow' => 'రేపు',
    'formats' => [
        'LT' => 'A h:mm',
        'LTS' => 'A h:mm:ss',
        'L' => 'DD/MM/YYYY',
        'LL' => 'D MMMM YYYY',
        'LLL' => 'D MMMM YYYY, A h:mm',
        'LLLL' => 'dddd, D MMMM YYYY, A h:mm',
    ],
    'calendar' => [
        'sameDay' => '[నేడు] LT',
        'nextDay' => '[రేపు] LT',
        'nextWeek' => 'dddd, LT',
        'lastDay' => '[నిన్న] LT',
        'lastWeek' => '[గత] dddd, LT',
        'sameElse' => 'L',
    ],
    'ordinal' => ':numberవ',
    'meridiem' => function ($hour) {
        if ($hour < 4) {
            return 'రాత్రి';
        }
        if ($hour < 10) {
            return 'ఉదయం';
        }
        if ($hour < 17) {
            return 'మధ్