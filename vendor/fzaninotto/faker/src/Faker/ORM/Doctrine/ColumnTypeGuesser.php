<?php

namespace Faker\Provider;

use Faker\Calculator\Iban;
use Faker\Calculator\Luhn;

class Payment extends Base
{
    public static $expirationDateFormat = "m/y";

    protected static $cardVendors = array(
        'Visa', 'Visa', 'Visa', 'Visa', 'Visa',
        'MasterCard', 'MasterCard', 'MasterCard', 'MasterCard', 'MasterCard',
        'American Express', 'Discover Card', 'Visa Retired'
    );

    /**
     * @var array List of card brand masks for generating valid credit card numbers
     * @see https://en.wikipedia.org/wiki/Payment_card_number Reference for existing prefixes
     * @see https://www.mastercard.us/en-us/issuers/get-support/2-series-bin-expansion.html MasterCard 2017 2-Series BIN Expansion
     */
    protected static $cardParams = array(
        'Visa' => array(
            "4539###########",
            "4556###########",
            "4916###########",
            "4532###########",
            "4929###########",
            "40240071#######",
            "4485###########",
            "4716###########",
            "4##############"
        ),
        'Visa Retired' => array(
            "4539########",
            "4556########",
            "4916########",
            "4532########",
            "4929########",
            "40240071####",
            "4485########",
            "4716########",
            "4###########",
        ),
        'MasterCard' => array(
            "2221###########",
            "23#############",
            "24#############",
            "25#############",
            "26#############",
            "2720###########",
            "51#############",
            "52#############",
            "53#############",
            "54#############",
            "55#############"
        ),
        'American Express' => array(
            "34############",
            "37############"
        ),
        'Discover Card' => array(
            "6011###########"
        ),
    );

    /**
     * @var array list of IBAN formats, source: @link https://www.swift.com/standards/data-standards/iban
     */
    protected static $ibanFormats = array(
        'AD' => array(array('n', 4),    array('n', 4),  array('c', 12)),
        'AE' => array(array('n', 3),    array('n', 16)),
        'AL' => array(array('n', 8),    array('c', 16)),
        'AT' => array(array('n', 5),    array('n', 11)),
        'AZ' => array(array('a', 4),    array('c', 20)),
