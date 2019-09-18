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
}, 'bm');

/*
 * Authors:
 * - Estelle Comment
 */
return [
    'year' => 'san :count',
    'a_year' => 'san kelen|san :count',
    'y' => 'san :count',
    'month' => 'kalo :count',
    'a_month' => 'kalo kelen|kalo :count',
    'm' => 'k. :count',
    'week' => 'dɔgɔkun :count',
    'a_week' => 'dɔgɔkun kelen',
    'w' => 'd. :count',
    'day' => 'tile :count',
    'd' => 't. :count',
    'a_day' => 'tile kelen|tile :count',
    'hour' => 'lɛrɛ :count',
    'a_hour' => 'lɛrɛ kelen|lɛrɛ :count',
    'h' => 'l. :count',
    'minute' => 'miniti :count',
    'a_minute' => 'miniti kelen|miniti :count',
    'min' => 'm. :count',
    'second' => 'sekondi :count',
    'a_second' => 'sanga dama dama|sekondi :count',
    's' => 'sek. :count',
    'ago' => 'a bɛ :time bɔ',
    'from_now' => ':time kɔnɔ',
    'formats' => [
        'LT' => 'HH:mm',
        'LTS' => 'HH:mm:ss',
        'L' => 'DD/MM/YYYY',
        'LL' => 'MMMM [tile] D [san] YYYY',
        'LLL' => 'MMMM [tile] D [san] YYYY [lɛrɛ] HH:mm',
        'LLLL' => 'dddd MMMM [tile] D [san] YYYY [lɛrɛ] HH:mm',
    ],
    'calendar' => [
        'sameDay' => '[Bi lɛrɛ] LT',
        'nextDay' => '[Sini lɛrɛ] LT',
        'nextWeek' => 'dd