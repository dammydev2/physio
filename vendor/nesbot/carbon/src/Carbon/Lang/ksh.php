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
 * - nootanghimire
 * - Josh Soref
 * - Nj Subedi
 * - JD Isaacks
 */
return [
    'year' => 'एक बर्ष|:count बर्ष',
    'y' => ':count वर्ष',
    'month' => 'एक महिना|:count महिना',
    'm' => ':count महिना',
    'week' => ':count हप्ता',
    'w' => ':count हप्ता',
    'day' => 'एक दिन|:count दिन',
    'd' => ':count दिन',
    'hour' => 'एक घण्टा|:count घण्टा',
    'h' => ':count घण्टा',
    'minute' => 'एक मिनेट|:count मिनेट',
    'min' => ':count मिनेट',
    'second' => 'केही क्षण|:count सेकेण्ड',
    's' => ':count सेकेण्ड',
    'ago' => ':time अगाडि',
    'from_now' => ':timeमा',
    'after' => ':time पछि',
    'before' => ':time अघि',
    'diff_yesterday' => 'हिजो',
    'diff_tomorrow' => 'भोलि',
    'formats' => [
        'LT' => 'Aको h:mm बजे',
        'LTS' => 'Aको h:mm:ss बजे',
        'L' => 'DD/MM/YYYY',
        'LL' => 'D MMMM YYYY',
        'LLL' => 'D MMMM YYYY, Aको h:mm बजे',
        'LLLL' => 'dddd, D MMMM YYYY, Aको h:mm बजे',
    ],
    'calendar' => [
        'sameDay' => '[आज] LT',
        'nextDay' => '[भोलि] LT',
        'nextWeek' => '[आउँदो] dddd[,] LT',
        'lastDay' => '[हिजो] LT',
        'lastWeek' => '[गएको] dddd[,] LT',
        'sameElse' => 'L',
    ],
    'meridiem' => function ($hour) {
        if ($hour < 3) {
            return 'रात�