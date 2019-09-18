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
}, 'yo');

/*
 * Authors:
 * - François B
 * - Atolagbe Abisoye
 */
return [
    'year' => 'ọdún :count',
    'a_year' => 'ọdún kan|ọdún :count',
    'month' => 'osù :count',
    'a_month' => 'osù kan|osù :count',
    'week' => 'ọsẹ :count',
    'a_week' => 'ọsẹ kan|ọsẹ :count',
    'day' => 'ọjọ́ :count',
    'a_day' => 'ọjọ́ kan|ọjọ́ :count',
    'hour' => 'wákati :count',
    'a_hour' => 'wákati kan|wákati :count',
    'minute' => 'ìsẹjú :count',
    'a_minute' => 'ìsẹjú kan|ìsẹjú :count',
    'second' => 'iaayá :count',
    'a_second' => 'ìsẹjú aayá die|aayá :count',
    'ago' => ':time kọjá',
    'from_now' => 'ní :time',
    'diff_yesterday' => 'Àna',
    'diff_tomorrow' => 'Ọ̀la',
    'formats' => [
        'LT' => 'h:mm A',
        'LTS' => 'h:mm:ss A',
        'L' => 'DD/MM/YYYY',
        'LL' => 'D MMMM YYYY',
        'LLL' => 'D MMMM YYYY h:mm A',
        'LLLL' => 'dddd, D MMMM YYYY h:mm A',
    ],
    'calendar' => [
        'sameDay' => '[Ònì ni] LT',
        'nextDay' => '[Ọ̀la ni] LT',
        'nextWeek' => 'dd