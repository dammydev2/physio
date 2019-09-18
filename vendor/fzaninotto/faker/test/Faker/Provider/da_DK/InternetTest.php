<?php

namespace Faker\Test\Provider\ro_RO;

use Faker\Generator;
use Faker\Provider\DateTime;
use Faker\Provider\ro_RO\Person;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    const TEST_CNP_REGEX = '/^[1-9][0-9]{2}(?:0[1-9]|1[012])(?:0[1-9]|[12][0-9]|3[01])(?:0[1-9]|[123][0-9]|4[0-6]|5[12])[0-9]{3}[0-9]$/';

    /**
     * @var \Faker\Generator
     *
     */
    protected $faker;

    public function setUp()
    {
        $faker = new Generator();
        $faker->addProvider(new DateTime($faker));
        $faker->addProvider(new Person($faker));
        $faker->setDefaultTimezone('Europe/Bucharest');
        $this->faker = $faker;
    }

    public function t