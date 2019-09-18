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
 * - François B
 * - Fidel Pita
 * - JD Isaacks
 * - Diego Vilariño
 * - Sebastian Thierer
 */
return [
    'year' => ':count ano|:count anos',
    'a_year' => 'un ano|:count anos',
    'y' => ':count a.',
    'month' => ':count mes|:count meses',
    'a_month' => 'un mes|:count meses',
    'm' => ':count mes.',
    'week' => ':count semana|:count semanas',
    'a_week' => 'unha semana|:count semanas',
    'w' => ':count sem.',
    'day' => ':count día|:count días',
    'a_day' => 'un día|:count días',
    'd' => ':count d.',
    'hour' => ':count hora|:count horas',
    'a_hour' => 'unha hora|:count horas',
    'h' => ':count h.',
    'minute' => ':count minuto|:count minutos',
    'a_minute' => 'un minuto|:count minutos',
    'min' => ':count min.',
    'second' => ':count segundo|:count segundos',
    'a_second' => 'uns segundos|:count segundos',
    's' => ':count seg.',
    'ago' => 'hai :time',
    'from_now' => function ($time) {
        if (substr($time, 0, 2) === 'un') {
            return "