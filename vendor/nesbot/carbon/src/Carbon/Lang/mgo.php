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
 * - Bari Badamshin
 * - Jørn Ølmheim
 * - François B
 * - Tim Fish
 * - Коренберг Марк (imac)
 * - Serhan Apaydın
 * - RomeroMsk
 * - vsn4ik
 * - JD Isaacks
 * - Bari Badamshin
 * - Jørn Ølmheim
 * - François B
 * - Коренберг Марк (imac)
 * - Serhan Apaydın
 * - RomeroMsk
 * - vsn4ik
 * - JD Isaacks
 * - Fellzo
 * - andrey-helldar
 * - Pavel Skripkin (psxx)
 * - AlexWalkerson
 * - Vladislav UnsealedOne
 */
$transformDiff = function ($input) {
    return strtr($input, [
        'неделя' => 'неделю',
        'секунда' => 'секунду',
        'минута' => 'минуту',
    ]);
};

return [
    'year' => ':count год|:count года|:count лет',
    'y' => ':count г.|:count г.|:count л.',
    'a_year' => '{1}год|:count год|:count года|:count лет',
    'month' => ':count месяц|:count месяца|:count месяцев',
    'm' => ':count мес.',
    'a_month' => '{1}месяц|:count м�