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
}, 'ss');

/*
 * Authors:
 * - François B
 * - Nicolai Davies
 */
return [
    'year' => 'umnyaka|:count iminyaka',
    'month' => 'inyanga|:count tinyanga',
    'week' => ':count liviki|:count emaviki',
    'day' => 'lilanga|:count emalanga',
    'hour' => 'lihora|:count emahora',
    'minute' => 'umzuzu|:count emizuzu',
    'second' => 'emizuzwana lomcane|:count mzuzwana',
    'ago' => 'wenteka nga :time',
    'from_now' => 'nga :time',
    'diff_yesterday' => 'Itolo',
    'diff_tomorrow' => 'Kusasa',
    'formats' => [
        'LT' => 'h:mm A