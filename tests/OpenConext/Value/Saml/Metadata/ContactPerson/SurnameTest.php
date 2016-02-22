<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;

class SurnameTest extends UnitTest
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
    public function only_non_empty_strings_are_valid_surnames($invalidValue)
    {
        new Surname($invalidValue);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function equality_can_be_verified()
    {
        $base      = new Surname('Simpson');
        $theSame   = new Surname('Simpson');
        $different = new Surname('Doe');

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($different));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function the_surname_can_be_retrieved()
    {
        $name = 'Simpson';

        $surname = new Surname($name);

        $this->assertEquals($name, $surname->getSurname());
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function a_surname_can_be_cast_to_string()
    {
        $name = 'Simpson';

        $surname = new Surname($name);

        $this->assertEquals($name, (string) $surname);
    }
}
