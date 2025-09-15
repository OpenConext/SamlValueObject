<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Exception\InvalidArgumentException;


class GivenNameTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @group        metadata
     * @group        contactperson
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     *
     * @param mixed $invalidValue
     */
    public function only_non_empty_strings_are_valid_given_names($invalidValue)
    {
        $this->expectException(\InvalidArgumentException::class);
        new GivenName($invalidValue);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function equality_can_be_verified()
    {
        $base      = new GivenName('Homer');
        $theSame   = new GivenName('Homer');
        $different = new GivenName('Marge');

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($different));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function the_given_name_can_be_retrieved()
    {
        $name = 'Homer';

        $givenName = new GivenName($name);

        $this->assertEquals($name, $givenName->getGivenName());
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function deserializing_a_serialized_given_name_results_in_an_equal_value_object()
    {
        $givenName = 'Homer';

        $original     = new GivenName($givenName);
        $deserialized = GivenName::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
        $this->assertEquals($givenName, $deserialized->getGivenName());
    }

    /**
     * @test
     * @group        metadata
     * @group        contactperson
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     *
     * @param mixed $invalidData
     */
    public function deserialization_requires_the_presence_of_the_correct_data($invalidData)
    {
        $this->expectException(\InvalidArgumentException::class);
        GivenName::deserialize($invalidData);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function a_given_name_can_be_cast_to_string()
    {
        $name = 'Homer';

        $givenName = new GivenName($name);

        $this->assertEquals($name, (string) $givenName);
    }
}
