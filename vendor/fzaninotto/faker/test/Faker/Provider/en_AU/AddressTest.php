<?php

namespace Faker\Test\Provider\uk_UA;

use Faker\Generator;
use Faker\Provider\uk_UA\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{

    /**
     * @var Generator
     */
    private $faker;

    public function setUp()
    {
        $faker = new Generator();
        $faker->addProvider(new Address($faker));
        $this->faker = $faker;
    }

    public function testPostCodeIsValid()
    {
        $main = '[0-9]{5}';
        $pattern = "/^($main)|($main-[0-9]{3})+$/";
        $postcode = $this->faker->postcode;
        $this->assertRegExp($pattern, $postcode, 'Post code ' . $postcode . ' is wrong!');
    }

    public function testEmptySuffixes()
    {
        $this->assertEmpty($this->faker->citySuffix, 'City suffix should be empty!');
        $this->assertEmpty($this->faker->streetSuffix, 'Street suffix should be empty!');
    }

    public function testStreetCyrOnly()
    {
        $pattern = "/[0-9А-ЩЯІЇЄЮа-щяіїєюьIVXCM][0-9А-ЩЯІЇЄЮа-щяіїєюь \'-.]*[А-Яа-я.]/u";
        $s