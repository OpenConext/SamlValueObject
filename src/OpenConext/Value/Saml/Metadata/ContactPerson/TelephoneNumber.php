<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Serializable;

final class TelephoneNumber implements Serializable
{
    /**
     * @var string
     */
    private $telephoneNumber;

    /**
     * @param string $telephoneNumber
     */
    public function __construct($telephoneNumber)
    {
        Assertion::nonEmptyString($telephoneNumber, 'telephoneNumber');

        $this->telephoneNumber = $telephoneNumber;
    }

    /**
     * @param TelephoneNumber $other
     * @return bool
     */
    public function equals(TelephoneNumber $other)
    {
        return $this->telephoneNumber === $other->telephoneNumber;
    }

    /**
     * @return string
     */
    public function getTelephoneNumber()
    {
        return $this->telephoneNumber;
    }

    public static function deserialize($data)
    {
        return new self($data);
    }

    public function serialize()
    {
        return $this->telephoneNumber;
    }

    public function __toString()
    {
        return $this->telephoneNumber;
    }
}
