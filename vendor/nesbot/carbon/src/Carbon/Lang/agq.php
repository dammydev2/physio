<?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
return array_replace_recursive(require __DIR__.'/bo.php', [
    'meridiem' => ['སྔ་དྲོ་', 'ཕྱི་དྲོ་'],
    'weekdays' => ['གཟའ་ཉི་མ་', 'གཟའ་ཟླ་བ་', 'གཟའ་མིག་དམར་', 'གཟའ་ལྷག་པ་', 'གཟའ་ཕུར་བུ་', 'གཟའ་པ་སངས་', 'གཟའ་སྤེན་པ་'],
    'weekdays_short' => ['ཉི་མ་', 'ཟླ་བ་', 'མིག་དམར་', 'ལྷག་པ་', 'ཕུར་བུ་', 'པ་སངས་', 'སྤེན་པ་'],
    'weekdays_min' => ['ཉི་མ་', 'ཟླ་བ་', 'མིག་དམར་', 'ལྷག་པ་', 'ཕུར་བུ་', 'པ་སངས་', 'སྤེན་པ་'],
    'months' => ['ཟླ་བ་དང་པོ', 'ཟླ་བ་གཉིས་པ', 'ཟླ་བ་གསུམ་པ', 'ཟླ་བ་བཞི་པ', 'ཟླ་བ་ལྔ་པ', 'ཟླ་བ་དྲུག་པ', 'ཟླ་བ་བདུན་པ', 'ཟླ་བ་བརྒྱད་པ', 'ཟླ་བ་�