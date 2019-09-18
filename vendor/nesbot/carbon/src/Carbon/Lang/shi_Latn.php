<?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
$processHoursFunction = function (\Carbon\CarbonInterface $date, string $format) {
    return $format.'о'.($date->hour === 11 ? 'б' : '').'] LT';
};

/*
 * Authors:
 * - Kunal Marwaha
 * - Josh Soref
 * - François B
 * - Tim Fish
 * - Serhan Apaydın
 * - Max Mykhailenko
 * - JD Isaacks
 * - Max Kovpak
 * - AucT
 * - Philippe Vaucher
 * - Ilya Shaplyko
 * - Vadym Ievsieiev
 * - Denys Kurets
 * - Igor Kasyanchuk
 * - Tsutomu Kuroda
 * - tjku
 * - Max Melentiev
 * - Oleh
 * - epaminond
 * - Juanito Fatas
 * - Vitalii Khustochka
 * - Akira Matsuda
 * - Christopher Dell
 * - Enrique Vidal
 * - Simone Carletti
 * - Aaron Patterson
 * - Andriy Tyurnikov
 * - Nicolás Hock Isaza
 * - Iwakura Taro
 * - Andrii Ponomarov
 * - alecrabbit
 * - vystepanenko
 * - AlexWalkerson
 */
return [
    'year' => ':count рік|:count роки|:count років',
    'y' => ':count рік|:count роки|:count років',
    'a_year' => '{1}рік|:count рік|:count роки|:count років',
    'month' => ':count місяць|:count 