<?php

namespace Faker\Test\Provider\ro_RO;

use Faker\Generator;
use Faker\Provider\DateTime;
use Faker\Provider\ro_RO\Person;
use PHPUnit\Framework\TestCase;

final class PersonTest extends TestCase
{
    const TEST_CNP_REGEX = '/^[1-9][0-9]{2}(?:0[1-9]|1[012])(?:0[1-9]|[12][0-9]|3[01])(?:0[1-9]|[123][0-9]|4[0-6]|5[12])[0-9]{3}[0-9]$/';

    /**
     * @var \Faker\Generator
     *
     */
    protected $faker;

    protected function setUp(): void
    {
        $faker = new Generator();
        $faker->addProvider(new DateTime($faker));
        $faker->addProvider(new Person($faker));
        $faker->setDefaultTimezone('Europe/Bucharest');
        $this->faker = $faker;
    }

    protected function tearDown(): void
    {
        $this->faker->setDefaultTimezone();
    }

    public static function invalidGenderProvider(): array
    {
        return array(
            array('elf'),
            array('ent'),
            array('fmle'),
            array('mal'),
        );
    }

    public static function invalidYearProvider(): array
    {
        return array(
            array(1652),
            array(1799),
            array(2100),
            array(2252),
        );
    }

    public static function validYearProvider(): array
    {
        return array(
            array(null),
            array(''),
            array(1800),
            array(1850),
            array(1900),
            array(1990),
            array(2000),
            array(2099),
        );
    }

    public static function validCountyCodeProvider(): array
    {
        return array(
            array('AB'), array('AR'), array('AG'), array('B'), array('BC'), array('BH'), array('BN'), array('BT'),
            array('BV'), array('BR'), array('BZ'), array('CS'), array('CL'), array('CJ'), array('CT'), array('CV'),
            array('DB'), array('DJ'), array('GL'), array('GR'), array('GJ'), array('HR'), array('HD'), array('IL'),
            array('IS'), array('IF'), array('MM'), array('MH'), array('MS'), array('NT'), array('OT'), array('PH'),
            array('SM'), array('SJ'), array('SB'), array('SV'), array('TR'), array('TM'), array('TL'), array('VS'),
            array('VL'), array('VN'), array('B1'), array('B2'), array('B3'), array('B4'), array('B5'), array('B6')
        );
    }

    public static function invalidCountyCodeProvider(): array
    {
        return array(
            array('JK'), array('REW'), array('x'), array('FF'), array('aaaddadaada')
        );
    }

    public static function validInputDataProvider(): array
    {
        return array(
            array(Person::GENDER_MALE, '1981-06-16','B2', true, '181061642'),
            array(Person::GENDER_FEMALE, '1981-06-16','B2', true, '281061642'),
            array(Person::GENDER_MALE, '1981-06-16','B2', false, '981061642'),
            array(Person::GENDER_FEMALE, '1981-06-16','B2', false, '981061642'),
        );
    }
    /**
     *
     */
    public function test_allRandom_returnsValidCnp()
    {
        $cnp = $this->faker->cnp;
        $this->assertTrue(
            $this->isValidCnp($cnp),
            sprintf("Invalid CNP '%' generated", $cnp)
        );
    }

    /**
     *
     */
    public function test_validGender_returnsValidCnp()
    {
        $cnp = $this->faker->cnp(Person::GENDER_MALE);
        $this->assertTrue(
            $this->isValidMaleCnp($cnp),
            sprintf("Invalid CNP '%' generated for '%s' gender", $cnp, Person::GENDER_MALE)
        );

        $cnp = $this->faker->cnp(Person::GENDER_FEMALE);
        $this->assertTrue(
            $this->isValidFemaleCnp($cnp),
            sprintf("Invalid CNP '%' generated for '%s' gender", $cnp, Person::GENDER_FEMALE)
        );
    }

    /**
     * @param string $value
     *
     * @dataProvider invalidGenderProvider
     */
    public function test_invalidGender_throwsException($value)
    {
        $this->expectException('InvalidArgumentException');
        $this->faker->cnp($value);
    }

    /**
     * @param string $value year of birth
     *
     * @dataProvider validYearProvider
     */
    public function test_validYear_returnsValidCnp($value)
    {
        $cnp = $this->faker->cnp(null, $value);
        $this->assertTrue(
            $this->isValidCnp($cnp),
            sprintf("Invalid CNP '%' generated for valid year '%s'", $cnp, $value)
        );
    }

    /**
     * @param string $value year of birth
     *
     * @dataProvider invalidYearProvider
     */
    public function test_invalidYear_throwsException($value)
    {
        $this->expectException('InvalidArgumentException');
        $this->faker->cnp(null, $value);
    }

    /**
     * @param $value
     * @dataProvider validCountyCodeProvider
     */
    public function test_validCountyCode_returnsValidCnp($value)
    {
        $cnp = $this->faker->cnp(null, null, $value);
        $this->assertTrue(
            $this->isValidCnp($cnp),
            sprintf("Invalid CNP '%' generated for valid year '%s'", $cnp, $value)
        );
    }

    /**
     * @param $value
     * @dataProvider invalidCountyCodeProvider
     */
    public function test_invalidCountyCode_throwsException($value)
    {
        $this->expectException('InvalidArgumentException');
        $this->faker->cnp(null, null, $value);
    }

    /**
     *
     */
    public function test_nonResident_returnsValidCnp()
    {
        $cnp = $this->faker->cnp(null, null, null, false);
        $this->assertTrue(
            $this->isValidCnp($cnp),
            sprintf("Invalid CNP '%' generated for non resident", $cnp)
        );
        $this->assertStringStartsWith(
            '9',
            $cnp,
            sprintf("Invalid CNP '%' generated for non resident (should start with 9)", $cnp)
        );
    }

    /**
     *
     * @param $gender
     * @param $dateOfBirth
     * @param $county
     * @param $isResident
     * @param $expectedCnpStart
     *
     * @dataProvider validInputDataProvider
     */
    public function test_validInputData_returnsValidCnp($gender, $dateOfBirth, $county, $isResident, $expectedCnpStart)
    {
        $cnp = $this->faker->cnp($gender, $dateOfBirth, $county, $isResident);
        $this->assertStringStartsWith(
            $expectedCnpStart,
            $cnp,
            sprintf("Invalid CNP '%' generated for non valid data", $cnp)
        );
    }


    protected function isValidFemaleCnp($value)
    {
        return $this->isValidCnp($value) && in_array($value[0], array(2, 4, 6, 8, 9));
    }

    protected function isValidMaleCnp($value)
    {
        return $this->isValidCnp($value) && in_array($value[0], array(1, 3, 5, 7, 9));
    }

    protected function isValidCnp($cnp)
    {
        if (preg_match(static::TEST_CNP_REGEX, $cnp) !== false) {
            $checkNumber = 279146358279;

            $checksum = 0;
            foreach (range(0, 11) as $digit) {
                $checksum += (int)substr($cnp, $digit, 1) * (int)substr($checkNumber, $digit, 1);
            }
            $checksum %= 11;
            $checksum = $checksum == 10 ? 1 : $checksum;

            if ($checksum == substr($cnp, -1)) {
                return true;
            }
        }

        return false;
    }
}
