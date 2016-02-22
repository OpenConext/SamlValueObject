<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Exception\InvalidArgumentException;

final class GivenName
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
        if (!is_string($givenName) || trim($givenName) === '') {
            throw InvalidArgumentException::invalidType('non-blank string', 'givenName', $givenName);
        }

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

    public function __toString()
    {
        return $this->givenName;
    }
}
