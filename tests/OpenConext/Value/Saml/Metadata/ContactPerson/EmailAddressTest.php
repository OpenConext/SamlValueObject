<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Exception\InvalidArgumentException;


class EmailAddressTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     *
     * @param mixed $invalidAddress
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notRfc822CompliantEmail')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function an_rfc_822_compliant_email_address_is_required($invalidAddress)
    {
        $this->expectException(\InvalidArgumentException::class);
        new EmailAddress($invalidAddress);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function equality_can_be_verified()
    {
        $base      = new EmailAddress('OpenConext@domain.invalid');
        $theSame   = new EmailAddress('OpenConext@domain.invalid');
        $different = new EmailAddress('someone@domain.invalid');

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($different));
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_email_address_can_be_retrieved()
    {
        $email = 'OpenConext@domain.invalid';

        $emailAddress = new EmailAddress($email);

        $this->assertEquals($email, $emailAddress->getEmailAddress());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserializing_an_email_address_results_in_an_equal_value_object()
    {
        $emailAddress = 'homer@domain.invalid';

        $original     = new EmailAddress($emailAddress);
        $deserialized = EmailAddress::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
        $this->assertEquals($emailAddress, $deserialized->getEmailAddress());
    }

    /**
     *
     *
     * @param mixed $invalidData
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notRfc822CompliantEmail')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserialization_requires_valid_data($invalidData)
    {
        $this->expectException(\InvalidArgumentException::class);
        EmailAddress::deserialize($invalidData);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function email_address_can_be_cast_to_string()
    {
        $email = 'OpenConext@domain.invalid';

        $emailAddress = new EmailAddress($email);

        $this->assertEquals($email, (string) $emailAddress);
    }
}
