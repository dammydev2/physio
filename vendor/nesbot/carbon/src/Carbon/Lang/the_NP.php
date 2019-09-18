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
 * - tarunvelli
 * - Eddie
 * - KID
 * - shankesgk2
 */
return [
    'year' => ':count 年',
    'y' => ':count年',
    'month' => ':count 個月',
    'm' => ':count個月',
    'week' => ':count 周',
    'w' => ':count周',
    'day' => ':count 天',
    'd' => ':count天',
    'hour' => ':count 小時',
    'h' => ':count小時',
    'minute' => ':count 分鐘',
    'min' => ':count分鐘',
    'second' => ':count 秒',
    'a_second' => '{1}幾秒|]1,Inf[:count 秒',
    's' => ':count秒',
    'ago' => ':time前',
    'from_now' => ':time內',
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
                return 