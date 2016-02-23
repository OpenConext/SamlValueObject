<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Serializable;

final class Company implements Serializable
{
    /**
     * @var string
     */
    private $company;

    /**
     ** @param string $company
     */
    public function __construct($company)
    {
        Assertion::nonEmptyString($company, 'company');

        $this->company = $company;
    }

    /**
     * @param Company $other
     * @return bool
     */
    public function equals(Company $other)
    {
        return $this->company === $other->company;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    public static function deserialize($data)
    {
        return new self($data);
    }

    public function serialize()
    {
        return $this->company;
    }

    public function __toString()
    {
        return $this->company;
    }
}
