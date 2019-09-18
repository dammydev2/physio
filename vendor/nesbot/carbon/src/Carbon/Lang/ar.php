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
 * - mestremuten
 * - François B
 * - Marc Ordinas i Llopis
 * - Pere Orga
 * - JD Isaacks
 * - Quentí
 * - Víctor Díaz
 * - Xavi
 */
return [
    'year' => ':count any|:count anys',
    'a_year' => 'un any|:count anys',
    'y' => ':count a.',
    'month' => ':count mes|:count mesos',
    'a_month' => 'un mes|:count mesos',
    'm' => ':count me.',
    'week' => ':count setmana|:count setmanes',
    'a_week' => 'una setmana|:count setmanes',
    'w' => ':count st.',
    'day' => ':count dia|:count dies',
    'a_day' => 'un dia|:count dies',
    'd' => ':count d.',
    'hour' => ':count hora|:count hores',
    'a_hour' => 'una hora|:count hores',
    'h' => ':count h.',
    'minute' => ':count minut|:count minuts',
    'a_minute' => 'un minut|:count minuts',
    'min' => ':count mi.',
    'second' => ':count segon|:count segons',
    'a_second' => 'uns segons|:count segons',
    's' => ':count sg.',
    'ago' => 'fa :time',
    'from_now' => 'd\'aquí :time',
    'after' => ':time després',
    'before' => ':time abans',
    'diff_now' => 'ara mateix',
    'diff_yesterday' => 'ahir',
    'diff_tomorrow' => 'demà',
    'diff_before_yesterday' => 'abans d\'ahir',
    'diff_after_tomorrow' => 'demà passat',
    'period_recurrences' => ':count cop|:count cops',
    'period_interval' => 'cada :interval',
    'period_start_date' => 'de :date',
    'period_end_date' => 'fins a :date',
    'formats' => [
        'LT' => 'H:mm',
        'LTS' => 'H:mm:ss',
        'L' => 'DD/MM/YYYY',
        'LL' => 'D MMMM [de] YYYY',
        'LLL' => 'D MMMM [de] YYYY [a les] H:mm',
        'LLLL' => 'dddd D MMMM [de] YYYY [a les] H:mm',
    ],
    'calendar' => [
        'sameDay' => function (\Carbon\CarbonInterface $current) {
            return '[avui a '.($current->hour !== 1 ? 'les' : 'la').'] LT';
        },
        'nextDay' => function (\Carbon\CarbonInterface $current) {
            return '[demà a '.($current->hour !== 1 ? 'les' : 'la').'] LT';
        },
        'nextWeek' => function (\Carbon\CarbonInterface $current) {
            return 'dddd [a '.($current->hour !== 1 ? 'les' : 'la').'] LT';
        },
        'lastDay' => function (\Carbon\CarbonInterface $current) {
            return '[ahir a '.($current->hour !== 1 ? 'les' : 'la').'] LT';
        },
        'lastWeek' => function (\Carbon\CarbonInterface $current) {
            return '[el] dddd [passat a '.($current->hour !== 1 ? 'les' : 'la').'] LT';
        },
        'sameElse' => 'L',
    ],
    'ordinal' => function ($number, $period) {
        r