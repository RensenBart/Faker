<?php


namespace Faker\Test\Calculator;


use Faker\Calculator\Ean;
use PHPUnit\Framework\TestCase;

final class EanTest extends TestCase
{
    public static function Ean8checksumProvider(): array
    {
        return array(
            array('1234567', '0'),
            array('2345678', '5'),
            array('3456789', '0'),
        );
    }

    public static function ean8ValidationProvider(): array
    {
        return array(
            array('1234567891231', true),
            array('2354698521469', true),
            array('3001092650834', false),
            array('3921092190838', false),
        );
    }

    /**
     * @dataProvider Ean8checksumProvider
     */
    public function testChecksumEan8($partial, $checksum)
    {
        $this->assertEquals($checksum, Ean::checksum($partial));
    }

    /**
     * @dataProvider ean8ValidationProvider
     */
    public function testEan8Validation($ean8, $valid)
    {
        $this->assertTrue(Ean::isValid($ean8) === $valid);
    }

    public static function Ean13checksumProvider(): array
    {
        return array(
            array('123456789123', '1'),
            array('978020137962', '4'),
            array('235469852146', '9'),
            array('300109265083', '5'),
            array('392109219083', '7'),
        );
    }

    public static function ean13ValidationProvider(): array
    {
        return array(
            array('1234567891231', true),
            array('2354698521469', true),
            array('3001092650834', false),
            array('3921092190838', false),
        );
    }

    /**
     * @dataProvider Ean13checksumProvider
     */
    public function testChecksumEan13($partial, $checksum)
    {
        $this->assertEquals($checksum, Ean::checksum($partial));
    }

    /**
     * @dataProvider ean13ValidationProvider
     */
    public function testEan13Validation($ean13, $valid)
    {
        $this->assertTrue(Ean::isValid($ean13) === $valid);
    }

}
