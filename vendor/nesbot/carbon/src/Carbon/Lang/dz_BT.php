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
 * - mhamlet
 */
return [
    'year' => ':count տարի',
    'a_year' => 'տարի|:count տարի',
    'y' => ':countտ',
    'month' => ':count ամիս',
    'a_month' => 'ամիս|:count ամիս',
    'm' => ':countամ',
    'week' => ':count շաբաթ',
    'a_week' => 'շաբաթ|:count շաբաթ',
    'w' => ':countշ',
    'day' => ':count օր',
    'a_day' => 'օր|:count օր',
    'd' => ':countօր',
    'hour' => ':count ժամ',
    'a_hour' => 'ժամ|:count ժամ',
    'h' => ':countժ',
    'minute' => ':count րոպե',
    'a_minute' => 'րոպե|:count րոպե',
    'min' => ':countր',
    'second' => ':count վայրկյան',
    'a_second' => 'մի քանի վայրկյան|:count վայրկյան',
    's' => ':countվրկ',
    'ago' => ':time առաջ',
    'from_now' => ':time ներկա պահից',
    'after' => ':time հետո',
    'before' => ':time առաջ',
    'diff_yesterday' => 'երեկ',
    'diff_tomorrow' => 'վաղը',
    'formats' => [
        'LT' => 'HH:mm',
        'LTS' => 'HH:mm:ss',
        'L' => 'DD.MM.YYYY',
        'LL' => 'D MMMM YYYY թ.',
        'LLL' => 'D MMMM YYYY թ., HH:mm',
        'LLLL' => 'dddd, D MMMM YYYY թ., HH:mm',
    ],
    'calendar' => [
        'sameDay' => '[այսօր] LT',
        'nextDay' => '[վաղը] LT',
        'nextWeek' => 'dddd [օրը ժամը] LT',
        'lastDay' => '[երեկ] LT',
        'lastWeek' => '[անցած] dddd [օրը ժամը] LT',
        'sameElse' => 'L',
    ],
    'ordinal' => function ($number, $period) {
        switch ($period) {
            case 'DDD':
            case 'w':
            case 'W':
            case 'DDDo':
                return $number.($number === 1 ? '-ին' : '-րդ');
            default:
                return $number;
        }
    },
    'meridiem' => function ($hour) {
        if ($hour < 4) {
            return 'գիշերվա';
        }
        if ($hour < 12) {
            return 'առավոտվա';
        }
        if ($hour < 17) {
            return 'ցերեկվա';
        }

        return 'երեկոյան';
    },
    'months' => ['հունվարի