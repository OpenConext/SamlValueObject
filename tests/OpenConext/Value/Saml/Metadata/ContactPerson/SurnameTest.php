<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Exception\InvalidArgumentException;


class SurnameTest extends \PHPUnit\Framework\TestCase
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
    public function only_non_empty_strings_are_valid_surnames($invalidValue)
    {
        $this->expectException(\InvalidArgumentException::class);
        new Surname($invalidValue);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function equality_can_be_verified()
    {
        $base      = new Surname('Simpson');
        $theSame   = new Surname('Simpson');
        $different = new Surname('Doe');

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($different));
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_surname_can_be_retrieved()
    {
        $name = 'Simpson';

        $surname = new Surname($name);

        $this->assertEquals($name, $surname->getSurname());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserializing_a_serialized_surname_results_in_an_equal_value_object()
    {
        $surname = 'Simpson';

        $original     = new Surname($surname);
        $deserialized = Surname::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
        $this->assertEquals($surname, $deserialized->getSurname());
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
        Surname::deserialize($invalidData);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function a_surname_can_be_cast_to_string()
    {
        $name = 'Simpson';

        $surname = new Surname($name);

        $this->assertEquals($name, (string) $surname);
    }
}
