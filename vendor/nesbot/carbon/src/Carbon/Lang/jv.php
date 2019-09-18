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
 * - Sashko Todorov
 * - Josh Soref
 * - François B
 * - Serhan Apaydın
 * - Borislav Mickov
 * - JD Isaacks
 * - Tomi Atanasoski
 */
return [
    'year' => ':count година|:count години',
    'a_year' => 'година|:count години',
    'y' => ':count год.',
    'month' => ':count месец|:count месеци',
    'a_month' => 'месец|:count месеци',
    'm' => ':count месец|:count месеци',
    'week' => ':count седмица|:count седмици',
    'a_week' => 'седмица|:count седмици',
    'w' => ':count седмица|:count седмици',
    'day' => ':count ден|:count дена',
    'a_day' => 'ден|:count дена',
    'd' => ':count ден|:count дена',
    'hour' => ':count час|:count часа',
    'a_hour' => 'час|:count часа',
    'h' => ':count час|:count часа',
    'minute' => ':count минута|:count минути',
    'a_minute' => 'минута|:count минути',
    'min' => ':count мин.',
    'second' => ':count секунда|:count секунди',
    'a_second' => 'неколку секунди|:count секунди',
    's' => ':count сек.',
    'ago' => 'пред :time',
    'from_now' => 'после :time',
    'after' => 'по :time',
    'before' => 'пред :time',
    'formats' => [
        'LT' => 'H:mm',
        'LTS' => 'H:mm:ss',
        'L' => 'D.MM.YYYY',
        'LL' => 'D MMMM YYYY',
        'LLL' => 'D MMMM YYYY H:mm',
        'LLLL' => 'dddd, D MMMM YYYY H:mm',
    ],
    'calendar' => [
        'sameDay' => '[Денес во] LT',
        'nextDay' => '[Утре во] LT',
        'nextWeek' => '[Во] dddd [во] LT',
        'lastDay' => '[Вчера во] LT',
        'lastWeek' => function (\Carbon\CarbonInterface $date) {
            switch ($date->dayOfWeek) {
                ca