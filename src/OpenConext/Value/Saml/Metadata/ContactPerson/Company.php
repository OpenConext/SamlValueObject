<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Assert\Assertion;

final class Company
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

    public function __toString()
    {
        return $this->company;
    }
}
