<?php

namespace Faker\Test\Provider\en_ZA;

use Faker\Generator;
use Faker\Provider\en_ZA\PhoneNumber;
use PHPUnit\Framework\TestCase;

class PhoneNumberTest extends TestCase
{
    private $faker;

    public function setUp()
    {
        $faker = new Generator();
        $faker->addProvider(new PhoneNumber($faker));
        $this->faker = $faker;
    }

    public function testPhoneNumber()
    {
        for ($i = 0; $i < 10; $i++) {
            $number = $this->faker->phoneNumber;

            $digits = array_values(array_filter(str_split($number), 'ctype_digit'));

            // 10 digits
            if($digits[0] = 2 && $digits[1] == 7) {
                $this->assertLessThanOrEqual(11, count($digits));
            } else {
                $this->assertGreaterThanOrEqual(10, count($digits));
            }
        }
    }

    public function testTollFreePhoneNumber()
    {
        for ($i = 0; $i < 10; $i++) {
            $number = $this->faker->tollFreeNumber;
            $digits = array_values(array_filter(str_split($number), 'ctype_digit'));

            if (count($digits) === 11) {
                $this