<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;

class EmailAddressTest extends UnitTest
{
    /**
     * @test
     * @group metadata
     * @group contactperson
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notRfc822CompliantEmail()
     * @expectedException InvalidArgumentException
     *
     * @param mixed $invalidAddress
     */
    public function an_rfc_822_compliant_email_address_is_required($invalidAddress)
    {
        new EmailAddress($invalidAddress);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function equality_can_be_verified()
    {
        $base      = new EmailAddress('OpenConext@domain.invalid');
        $theSame   = new EmailAddress('OpenConext@domain.invalid');
        $different = new EmailAddress('someone@domain.invalid');

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($different));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function the_email_address_can_be_retrieved()
    {
        $email = 'OpenConext@domain.invalid';

        $emailAddress = new EmailAddress($email);

        $this->assertEquals($email, $emailAddress->getEmailAddress());
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function deserializing_an_email_address_results_in_an_equal_value_object()
    {
        $emailAddress = 'homer@domain.invalid';

        $original     = new EmailAddress($emailAddress);
        $deserialized = EmailAddress::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
        $this->assertEquals($emailAddress, $deserialized->getEmailAddress());
    }

    /**
     * @test
     * @group        metadata
     * @group        contactperson
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notRfc822CompliantEmail()
     * @expectedException InvalidArgumentException
     *
     * @param mixed $invalidData
     */
    public function deserialization_requires_valid_data($invalidData)
    {
        EmailAddress::deserialize($invalidData);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function email_address_can_be_cast_to_string()
    {
        $email = 'OpenConext@domain.invalid';

        $emailAddress = new EmailAddress($email);

        $this->assertEquals($email, (string) $emailAddress);
    }
}
