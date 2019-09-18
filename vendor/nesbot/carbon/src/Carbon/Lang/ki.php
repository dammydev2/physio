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
}, 'my');

/*
 * Authors:
 * - Josh Soref
 * - JD Isaacks
 * - Nay Lin Aung
 */
return [
    'year' => 'တစ်နှစ်|:count နှစ်',
    'y' => ':count နှစ်',
    'month' => 'တစ်လ|:count လ',
    'm' => ':count လ',
    'week' => ':count ပတ်',
    'w' => ':count ပတ်',
    'day' => 'တစ်ရက်|:count ရက်',
    'd' => ':count ရက်',
    'hour' => 'တစ်နာရီ|:count နာရီ',
    'h' => ':count နာရီ',
    'minute' => 'တစ်မိနစ်|:count မိနစ်',
    'min' => ':count မိနစ်',
    'second' => 'စက္ကန်.အနည်းငယ်|:count စက္ကန့်',
    's' => ':count စက္ကန့်',
    'ago' => 'လွန်ခဲ့သော :time က',
    'from_now' => 'လာမည့် :time မှာ',
    'after' => ':time ကြာပြီးနောက်',
    'before' => ':time မတိုင်ခင်',
    'diff_now' => 'အခုလေးတင်',
    'diff_yesterday' => 'မနေ့က',
    'diff_tomorrow' => 'မနက်ဖြန်',
    'diff_before_yesterday' => 'တမြန်နေ့က',
    'diff_after_tomorrow' => 'တဘက်ခါ',
    'period_recurrences' => ':count ကြိမ်',
    'formats' => [
        'LT' => 'Oh:Om A',
        'LTS' => 'Oh:Om:Os A',
        'L' => 'OD/OM/OY',
        'LL' => 'OD MMMM OY',
        'LLL' => 'OD MMMM OY Oh:Om A',
        'LLLL' => 'dddd OD MMMM OY Oh:Om A',
    ],
    'calendar' => [
        'sameDay' => '[ယနေ.] LT [မှာ]',
        'nextDay' => '[မနက်ဖြန်] LT [မှာ]',
        'nextWeek' => 'dddd LT [မှာ]',
        'lastDay' => '[မနေ.က] LT [မှာ]',
        'lastWeek' => '[ပြီးခဲ့သော] dddd LT [မှာ]',
        'sameElse' => 'L',
    