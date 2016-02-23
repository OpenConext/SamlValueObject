<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Serializable;

final class GivenName implements Serializable
{
    /**
     * @var string
     */
    private $givenName;

    /**
     * @param string $givenName
     */
    public function __construct($givenName)
    {
        Assertion::nonEmptyString($givenName, 'givenName');

        $this->givenName = $givenName;
    }

    /**
     * @param GivenName $other
     * @return bool
     */
    public function equals(GivenName $other)
    {
        return $this->givenName === $other->givenName;
    }

    /**
     * @return string
     */
    public function getGivenName()
    {
        return $this->givenName;
    }

    public static function deserialize($data)
    {
        return new self($data);
    }

    public function serialize()
    {
        return $this->givenName;
    }

    public function __toString()
    {
        return $this->givenName;
    }
}
