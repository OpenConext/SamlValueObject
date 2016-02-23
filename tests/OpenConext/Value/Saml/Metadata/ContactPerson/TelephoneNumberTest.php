<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;

class TelephoneNumberTest extends UnitTest
{
    /**
     * @test
     * @group        metadata
     * @group        contactperson
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     * @expectedException InvalidArgumentException
     *
     * @param mixed $invalidValue
     */
    public function only_non_empty_strings_are_valid_telphone_numbers($invalidValue)
    {
        new TelephoneNumber($invalidValue);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function equality_can_be_verified()
    {
        $base      = new TelephoneNumber('+99 (1) 12 34 56 78 90');
        $theSame   = new TelephoneNumber('+99 (1) 12 34 56 78 90');
        $different = new TelephoneNumber('+00 (1) 12 34 56 78 90');

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($different));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function the_telephone_number_can_be_retrieved()
    {
        $number = '+99 (1) 12 34 56 78 90';

        $telephoneNumber = new TelephoneNumber($number);

        $this->assertEquals($number, $telephoneNumber->getTelephoneNumber());
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function deserializing_a_serialized_telephone_number_results_in_an_equal_value_object()
    {
        $telephoneNumber = '+99 (0) 12 34 56 78 90';

        $original     = new TelephoneNumber($telephoneNumber);
        $deserialized = TelephoneNumber::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
        $this->assertEquals($telephoneNumber, $deserialized->getTelephoneNumber());
    }

    /**
     * @test
     * @group        metadata
     * @group        contactperson
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     * @expectedException InvalidArgumentException
     *
     * @param mixed $invalidData
     */
    public function deserialization_requires_the_presence_of_the_correct_data($invalidData)
    {
        TelephoneNumber::deserialize($invalidData);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function a_telephone_number_can_be_cast_to_string()
    {
        $number = '+99 (1) 12 34 56 78 90';

        $telephoneNumber = new TelephoneNumber($number);

        $this->assertEquals($number, (string) $telephoneNumber);
    }
}
