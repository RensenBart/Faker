<?php

namespace Faker\Test\Provider\de_CH;

use Faker\Generator;
use Faker\Provider\de_CH\Person;
use Faker\Provider\de_CH\Internet;
use Faker\Provider\de_CH\Company;
use PHPUnit\Framework\TestCase;

final class InternetTest extends TestCase
{

    /**
     * @var Faker\Generator
     */
    private $faker;

    protected function setUp():void
    {
        $faker = new Generator();
        $faker->addProvider(new Person($faker));
        $faker->addProvider(new Internet($faker));
        $faker->addProvider(new Company($faker));
        $this->faker = $faker;
    }

    /**
     * @test
     */
    public function emailIsValid()
    {
        $email = $this->faker->email();
        $this->assertNotFalse(filter_var($email, FILTER_VALIDATE_EMAIL));
    }
}
