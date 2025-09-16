<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Exception\InvalidArgumentException;


class GivenNameTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     *
     * @param mixed $invalidValue
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notStringOrEmptyString')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function only_non_empty_strings_are_valid_given_names($invalidValue)
    {
        $this->expectException(\InvalidArgumentException::class);
        new GivenName($invalidValue);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function equality_can_be_verified()
    {
        $base      = new GivenName('Homer');
        $theSame   = new GivenName('Homer');
        $different = new GivenName('Marge');

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($different));
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_given_name_can_be_retrieved()
    {
        $name = 'Homer';

        $givenName = new GivenName($name);

        $this->assertEquals($name, $givenName->getGivenName());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserializing_a_serialized_given_name_results_in_an_equal_value_object()
    {
        $givenName = 'Homer';

        $original     = new GivenName($givenName);
        $deserialized = GivenName::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
        $this->assertEquals($givenName, $deserialized->getGivenName());
    }

    /**
     *
     *
     * @param mixed $invalidData
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notStringOrEmptyString')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserialization_requires_the_presence_of_the_correct_data($invalidData)
    {
        $this->expectException(\InvalidArgumentException::class);
        GivenName::deserialize($invalidData);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function a_given_name_can_be_cast_to_string()
    {
        $name = 'Homer';

        $givenName = new GivenName($name);

        $this->assertEquals($name, (string) $givenName);
    }
}
