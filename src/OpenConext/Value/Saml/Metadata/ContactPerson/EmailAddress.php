<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Serializable;

final class EmailAddress implements Serializable
{
    /**
     * @var string
     */
    private $emailAddress;

    /**
     * @param string $emailAddress RFC 822 compliant email address
     */
    public function __construct($emailAddress)
    {
        Assertion::email($emailAddress);

        $this->emailAddress = $emailAddress;
    }

    /**
     * @param EmailAddress $other
     * @return bool
     */
    public function equals(EmailAddress $other)
    {
        return $this->emailAddress === $other->emailAddress;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public static function deserialize($data)
    {
        return new self($data);
    }

    public function serialize()
    {
        return $this->emailAddress;
    }

    public function __toString()
    {
        return $this->emailAddress;
    }
}
