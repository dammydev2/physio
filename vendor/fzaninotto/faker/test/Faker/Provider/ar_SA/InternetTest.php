<?php

namespace Faker\Test\Provider\pt_PT;

use Faker\Generator;
use Faker\Provider\pt_PT\Person;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{

    public function setUp()
    {
        $faker = new Generator();
        $faker->addProvider(new Person($faker));
        $this->faker = $faker;
    }

    public function testTaxpayerIdentificationNumberIsValid()
    {
        $tin = $this->faker->taxpayerIdentificationNumber();
        $this->assertTrue($this->isValidTin($tin), $tin);
    }

    /**
     *
     * @link http://pt.wikipedia.org/wiki/N%C3%BAmero_de_identifica%C3%A7%C3%A3o_fiscal
     *
     * @param type $tin
     *
     * @return boolean
     */
    public static function isVal