<?php

namespace Faker\Test\Provider\sv_SE;

use Faker\Calculator\Luhn;
use Faker\Generator;
use Faker\Provider\sv_SE\Person;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    /** @var Generator */
    protected $faker;

    public function setUp()
    {
        $faker = new Generator();
        $faker->addProvider(new Person($faker));
        $this->faker = $faker;
    }

    public function provideSeedAndExpectedReturn()
    {
        return array(
            array(1, '720727', '720727-5798'),
            array(2, '710414', '710414-5664'),
            array(3, '591012', '591012-4519'),
            array(4, '180307', '180307-0356'),
            array(5, '820904', '820904-7748')
        );