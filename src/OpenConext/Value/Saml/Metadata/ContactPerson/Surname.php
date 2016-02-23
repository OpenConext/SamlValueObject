<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Assert\Assertion;

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
        Assertion::nonEmptyString($name, 'name');

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
