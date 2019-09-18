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
 * - Adam
 * - monkeycon
 * - François B
 * - Jason Katz-Brown
 * - Chris Lam
 * - Serhan Apaydın
 * - Gary Lo
 * - JD Isaacks
 * - Chris Hemp
 * - Eddie
 * - KID
 * - shankesgk2
 */
return [
    'year' => ':count年',
    'y' => ':count年',
    'month' => ':count個月',
    'm' => ':count月',
    'week' => ':count週',
    'w' => ':count週',
    'day' => ':count天',
    'd' => ':count天',
    'hour' => ':count小時',
    'h' => ':count小時',
    'minute' => ':count分鐘',
    'min' => ':count分鐘',
    'second' => ':count秒',
    'a_second' => '{1}幾秒|]1,Inf[:count秒',
    's' => ':count秒',
    'ago' => ':time前',
    'from_now' => ':time內',
    'after' => ':time後',
    'before' => ':time前',
    'diff_yesterday' => '昨天',
    'diff_tomorrow' => '明天',
    'formats' => [
        'LT' => 'HH:mm',
        'LTS' => 'HH:mm:ss',
        'L' => 'YYYY/MM/DD',
        'LL' => 'YYYY年M月D日',
        'LLL' => 'YYYY年M月D日 HH:mm',
        'LLLL' => 'YYYY年M月D日dddd HH:mm',
    ],
    'calendar' => [
        'sameDay' => '[今天] LT',
        'nextDay' => '[明天] LT',
        'nextWeek' => '[下]dddd LT',
        'lastDay' => '[昨天] LT',
        'lastWeek' => '[上]dddd LT',
        'sameElse' => 'L',
    ],
    'ordinal' => function ($number, $period) {
        switch ($period) {
            case 'd':
            case 'D':
            case 'DDD':
                return $number.'日';
            case 'M':
                return $number.'月';
            case 'w':
            case 'W':
                return $number.'周';
            default:
                return $number;
        }
    },
    'meridiem' => function ($hour, $minute) {
        $time = $hour * 100 + $minute;
        if ($time <