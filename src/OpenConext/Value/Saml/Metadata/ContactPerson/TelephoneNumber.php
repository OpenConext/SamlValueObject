<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Assert\Assertion;

final class TelephoneNumber
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

    public function __toString()
    {
        return $this->telephoneNumber;
    }
}
