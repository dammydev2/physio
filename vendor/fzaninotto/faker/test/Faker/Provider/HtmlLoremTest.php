<?php

namespace Faker\Test\Provider\it_CH;

use Faker\Generator;
use Faker\Provider\it_CH\Address;
use Faker\Provider\it_CH\Person;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{

    /**
     * @var Faker\Generator
     */
    private $faker;

    public function setUp()
    {
        $faker = new Generator();
        $faker->addProvider(new Address($faker));
        $faker->addProvider(new Person($faker));
        $this->faker = $faker;
    }

    /**
     * @test
     */
    public function canton ()
    {
        $canton = $this->faker->canton();
        $this->assertInternalType('array', $canton);
        $this->assertCount(1, $canton);

        foreach ($canton as $cantonShort => $cantonName){
            $this->assertInt