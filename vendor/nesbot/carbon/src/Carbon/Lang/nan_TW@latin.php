<?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
$months = [
    'جنوري',
    'فيبروري',
    'مارچ',
    'اپريل',
    'مئي',
    'جون',
    'جولاءِ',
    'آگسٽ',
    'سيپٽمبر',
    'آڪٽوبر',
    'نومبر',
    'ڊسمبر',
];

$weekdays = [
    'آچر',
    'سومر',
    'اڱارو',
    'اربع',
    'خميس',
    'جمع',
    'ڇنڇر',
];

\Symfony\Component\Translation\PluralizationRules::set(function ($number) {
    return $number === 1 ? 0 : 1;
}, 'sd');

/*
 * Authors:
 * - Narain Sagar
 * - Sawood Alam
 * - Narain Sagar
 */
return [
    'year' => 'هڪ سال|:count سال',
    'month' => 'هڪ مهينو|:count مهينا',
    'week' => 'ھڪ ھفتو|:count هفتا',
    'day' => 'هڪ ڏينهن|:count ڏينهن',
    'h