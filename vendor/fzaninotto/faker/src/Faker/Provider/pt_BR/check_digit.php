<?php

namespace Faker\Provider\ro_RO;

class PhoneNumber extends \Faker\Provider\PhoneNumber
{
    protected static $normalFormats = array(
        'landline' => array(
            '021#######', // Bucharest
            '023#######',
            '024#######',
            '025#######',
            '026#######',
            '027#######', // non-geographic
            '031#######', // Bucharest
            '033#######',
            '034#######',
            '035#######',
            '036#######',
            '037#######', // non-geographic
        ),
        'mobile' => array(
            '07########',
        )
    );

    protected static $specialFormats = array(
        'toll-free' => array(
            '0800######',
            '0801######', // shared-cost numbers
            '0802######', // personal numbering
            '0806######', // virtual cards
            '0807######', // pre-paid cards
           