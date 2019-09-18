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
 * - bokideckonja
 * - Josh Soref
 * - François B
 * - shaishavgandhi05
 * - Serhan Apaydın
 * - JD Isaacks
 */
return [
    'year' => ':count godina|:count godine|:count godina',
    'y' => ':count godina|:count godine|:count godina',
    'month' => ':count mjesec|:count mjeseca|:count mjeseci',
    'm' => ':count mjesec|:count mjeseca|:count mjeseci',
    'week' => ':count nedjelja|:count nedjelje|:count nedjelja',
    'w' => ':count nedjelja|:count nedjelje|:count nedjelja',
    'day' => ':count dan|:count dana|:count dana',
    'd' => ':count dan|:count dana|:count dana',
    'hour' => ':count sat|:count sata|:count sati',
    'h' => ':count sat|:count sata|:count sati',
    'minute' => ':count minut|:count minuta|:count minuta',
    'min' => ':count minut|:count minuta|:count minuta',
    'second' => ':count sekund|:count sekunda|:count sekundi',
    's' => ':count sekund|:count sekunda|:count sekundi',
    'ago' => 'prije :time',
    'from_now' => 'za :time',
    'after' => 'nakon :time',
    'before' => ':time ranije',
    'diff_yesterday' => 'jučer',
    'diff_tomorrow' => 'sutra',
    'formats' => [
        'LT' => 'H:mm',
        'LTS' => 'H:mm:ss',
        'L' => 'DD.MM.YYYY',
        'LL' => 'D. MMMM YYYY',
        'LLL' => 'D. MMMM YYYY H:mm',
        'LLLL' => 'dddd, D. MMMM YYYY H:mm',
    ],
    'calendar' => [
        'sameDay' => '[danas u] LT',
        'nextDay' => '[sutra u] LT',
        'nextWeek' => function (\Carbon\CarbonInterface $current) {
            switch ($current->dayOfWeek) {
                case 0:
                    return '[u] [nedjelju] [u] LT';
                case 3:
                    return '[u] [srijedu] [u] LT';
                case 6:
                    return '[u] [subotu] [u] LT';
                default:
  