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
 * - Alessandro Di Felice
 * - François B
 * - Tim Fish
 * - Gabriel Monteagudo
 * - JD Isaacks
 * - yiannisdesp
 */
return [
    'year' => ':count χρόνος|:count χρόνια',
    'a_year' => 'ένας χρόνος|:count χρόνια',
    'y' => ':count χρό.',
    'month' => ':count μήνας|:count μήνες',
    'a_month' => 'ένας μήνας|:count μήνες',
    'm' => ':count μήν.',
    'week' => ':count εβδομάδα|:count εβδομάδες',
    'a_week' => 'μια εβδομάδα|:count εβδομάδες',
    'w' => ':count εβδ.',
    'day' => ':count μέρα|:count μέρες',
    'a_day' => 'μία μέρα|:count μέρες',
    'd' => ':count μέρ.',
    'hour' => ':count ώρα|:count ώρες',
    'a_hour' => 'μία ώρα|:count ώρες',
    'h' => ':count ώρα|:count ώρες',
    'minute' => ':count λεπτό|:count λεπτά',
    'a_minute' => 'ένα λεπτό|:count λεπτά',
    'min' => ':count λεπ.',
    'second' => ':count δευτερόλεπτο|:count δευτερόλεπτα',
    'a_second' => 'λίγα δευτερόλεπτα|:count δευτερόλεπτα',
    's' => ':count δευ.',
    'ago' => ':time πριν',
    'from_now' => 'σε :time',
    'after' => ':time μετά',
    'before' => ':time πριν',
    'formats' => [
        'LT' => 'h:mm A',
        'LTS' => 'h:mm:ss A',
        'L' => 'DD/MM/YYYY',
        'LL' => 'D MMMM YYYY',
        'LLL' => 'D MMMM YYYY h:mm A',
        'LLLL' => 'dddd, D MMMM YYYY h:mm A',
    ],
    'calendar' => [
        'sameDay' => '[Σήμερα {}] LT',
        'nextDay' => '[Αύριο {}] LT',
        'nextWeek' => 'dddd [{}] LT',
        'lastDay' => '[Χθες {}] LT',
        'lastWeek' => function (\Carbon\CarbonInterface $current) {
            switch ($current->dayOfWeek) {
    