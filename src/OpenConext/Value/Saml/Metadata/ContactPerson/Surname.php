<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Exception\InvalidArgumentException;

final class Surname
{
    /**
     * @var string
     */
    private $surname;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        if (!is_string($name) || trim($name) === '') {
            throw InvalidArgumentException::invalidType('non-empty string', 'name', $name);
        }

        $this->surname = $name;
    }

    /**
     * @param Surname $other
     * @return bool
     */
    public function equals(Surname $other)
    {
        return $this->surname === $other->surname;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    public function __toString()
    {
        return $this->surname;
    }
}
