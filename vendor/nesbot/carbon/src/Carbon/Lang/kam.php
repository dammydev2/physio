<?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
return [
    'year' => ':count वर्ष',
    'y' => ':count वर्ष',
    'month' => ':count महिना|:count महिने',
    'm' => ':count महिना|:count महिने',
    'week' => ':count आठवडा|:count आठवडे',
    'w' => ':count आठवडा|:count आठवडे',
    'day' => ':count दिवस',
    'd' => ':count दिवस',
    'hour' => ':count तास',
    'h' => ':count तास',
    'minute' => ':count मिनिटे',
    'min' => ':count मिनिटे',
    'second' => ':count सेकंद',
    's' => ':count सेकंद',

    'ago' => ':timeपूर्वी',
    'from_now' => ':timeमध्ये',

    'diff_yesterday' => 'काल',
    'diff_tomorrow' => 'उद्या',

    'formats' => [
        'LT' => 'A h:mm वाजता',
        'LTS' => 'A h:mm:ss वाजता',
        'L' => 'DD/MM/YYYY',
        'LL' => 'D MMMM YYYY',
        'LLL' => 'D MMMM YYYY, A h:mm वाजता',
        'LLLL' => 'dddd, D MMMM YYYY, A h:mm वाजता',
    ],

    'calendar' => [
        'sameDay' => '[आज] LT',
        'nextDay' => '[उद्या] LT',
        'nextWeek' => 'dddd, LT',
        'lastDay' => '[काल] LT',
        'lastWeek' => '[मागील] dddd, LT',
        'sameElse' => 'L',
    ],

    'meridiem' => function ($hour) {
        if ($hour < 4) {
            return 'रात्री';
        }
        if ($hour < 10) {
            return 'सकाळी';
        }
        if ($hour < 17) {
            return 'दुपारी';
        }
        if ($hour < 20) {
            return 'सायंकाळी';
        }

        return 'रात�