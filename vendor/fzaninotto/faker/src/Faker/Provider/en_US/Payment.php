<?php

namespace Faker\Provider\en_ZA;

class PhoneNumber extends \Faker\Provider\PhoneNumber
{
    protected static $formats = array(
        '+27({{areaCode}})#######',
        '+27{{areaCode}}#######',
        '0{{areaCode}}#######',
        '0{{areaCode}} ### ####',
        '0{{areaCode}}-###-####',
    );

    protected static $cellphoneFormats = array(
        '+27{{cellphoneCode}}#######',
        '0{{cellphoneCode}}#######',
        '0{{cellphoneCode}} ### ####',
        '0{{cellphoneCode}}-###-####',
    );

    protected static $specialFormats = array(
        '{{specialCode}}#######',
        '{{specialCode}} ### ####',
        '{{specialCode}}-###-####',
        '({{specialCode}})###-####',
    );

    protected static $tollFreeAreaCodes = array(
        '0800', '0860', '0861', '0862',
    );

    /**
     * @see https://en.wikipedia.org/wiki/Telephone_numbers_in_South_Africa
     */
    public static function areaCode()
    {
        $digits[] = self::numberBetween(1, 5);
        switch ($digits[0]) {
            case 1:
                $digits[] = self::numberBetween(1, 8);
                break;
            case 2:
               