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
}, 'tet');

/*
 * Authors:
 * - Joshua Brooks
 * - François B
 */
return [
    'year' => 'tinan :count',
    'a_year' => 'tinan ida|tinan :count',
    'month' => 'fulan :count',
    'a_month' => 'fulan ida|fulan :count',
    'week' => 'semana :count',
    'a_week' => 'semana ida|semana :count',
    'day' => 'loron :count',
    'a_day' => 'loron ida|loron :count',
    'hour' => 'oras :count',
    'a_hour' => 'oras ida|oras :count',
    'minute' => 'minutu :count',
    'a_minute' => 'minutu ida|minutu :count',
    'second' => 'segundu :count',
    'a_second' => 'segundu balun|segundu :count',
    'ago' => ':time liuba',
    'from_now' => 'iha :time',
    'diff_yesterday' => 'Horiseik',
    'diff_tomorrow' => 'Aban',
    'formats' => [
        'LT' => 'HH:mm',
        'LT