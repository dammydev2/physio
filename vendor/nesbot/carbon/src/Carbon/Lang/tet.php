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
 * - xuri
 * - sycuato
 * - bokideckonja
 * - Luo Ning
 */
return [
    'year' => ':count年',
    'y' => ':count年',
    'month' => ':count个月',
    'm' => ':count个月',
    'week' => ':count周',
    'w' => ':count周',
    'day' => ':count天',
    'd' => ':count天',
    'hour' => ':count小时',
    'h' => ':count小时',
    'minute' => ':count分钟',
    'min' => ':count分钟',
    'second' => ':count秒',
    's' => ':count秒',
    'ago' => ':time前',
    'from_now' => '距现在:time',
    'after' => ':time后',
    'before' => ':time前',
    'diff_yesterday' => '昨天',
    'diff_tomorrow' => '明天',
    'formats' => [
        'LT' => 'HH:mm',
        'LTS' => 'HH:mm:ss',
        'L' => 'YYYY/MM/DD',
        'LL' => 'YYYY年M月D日',
        'LLL' => 'YYYY年M月D日 A h点mm分',
        'LLLL' => 'YYYY年M月D日dddd A h点mm分',
    ],
    'calendar' => [
        'sameDay' => '[今天]LT',
        'nextDay' => '[明天]LT',
        'nextWeek' => '[下]ddddLT',
        'lastDay' => '[昨天]LT',
        'lastWeek' => '[上]ddddLT',
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
        if ($time < 600) {
            return '凌晨';
        }
        if ($time < 900) {
            return '早上';
        }
        if ($time < 1130) {
            return '上午';
        }
        if ($time < 1230) {
            return '中午';
        }
        if ($time < 1800) {
            r