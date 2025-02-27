<?php

namespace Faker\Test\Provider\en_NZ;

use Faker\Generator;
use Faker\Provider\en_NZ\PhoneNumber;
use PHPUnit\Framework\TestCase;

final class PhoneNumberTest extends TestCase
{

    /**
     * @var Faker\Generator
     */
    private $faker;

    protected function setUp():void
    {
        $faker = new Generator();
        $faker->addProvider(new PhoneNumber($faker));
        $this->faker = $faker;
    }

    public function testIfPhoneNumberCanReturnData()
    {
      $number = $this->faker->phoneNumber;
      $this->assertNotEmpty($number);
    }

    public function phoneNumberFormat()
    {
      $number = $this->faker->phoneNumber;
      $this->assertMatchesRegularExpression('/(^\([0]\d{1}\))(\d{7}$)|(^\([0][2]\d{1}\))(\d{6,8}$)|([0][8][0][0])([\s])(\d{5,8}$)/', $number);
    }
}
?>
