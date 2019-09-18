<?php

namespace Faker\Provider\pl_PL;

use DateTime;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    /**
     * @var Generator
     */
    private $faker;

    public function setUp()
    {
        $faker = new Generator();
        $faker->addProvider(new Person($faker));
        $this->faker = $faker;
    }

    public function testPeselLenght()
    {
        $pesel = $this->faker->pesel();

        $this->assertEquals(11, strlen($pesel));
    }

    public function testPeselDate()
    {
        $date  = new DateTime('1990-01-01');
        $pesel = $this->faker->pesel($date);

        $this->assertEquals('90', substr($pesel, 0, 2));
        $this->assertEquals('01', substr($pesel, 2, 2));
        $this->assertEquals('01', substr($pesel, 4, 2));
    }

    public function testPeselDateWithYearAfter2000()
    {
        $date  = new DateTime('2001-01-01');
       