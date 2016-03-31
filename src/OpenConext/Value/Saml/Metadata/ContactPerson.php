<?php

namespace OpenConext\Value\Saml\Metadata;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Saml\Metadata\ContactPerson\Company;
use OpenConext\Value\Saml\Metadata\ContactPerson\ContactType;
use OpenConext\Value\Saml\Metadata\ContactPerson\EmailAddressList;
use OpenConext\Value\Saml\Metadata\ContactPerson\GivenName;
use OpenConext\Value\Saml\Metadata\ContactPerson\Surname;
use OpenConext\Value\Saml\Metadata\ContactPerson\TelephoneNumberList;
use OpenConext\Value\Serializable;

final class ContactPerson implements Serializable
{
    /**
     * @var ContactType
     */
    private $contactType;

    /**
     * @var EmailAddressList
     */
    private $emailAddressList;

    /**
     * @var TelephoneNumberList
     */
    private $telephoneNumberList;

    /**
     * @var GivenName|null
     */
    private $givenName;

    /**
     * @var Surname|null
     */
    private $surname;

    /**
     * @var Company|null
     */
    private $company;

    public function __construct(
        ContactType $contactType,
        EmailAddressList $emailAddressList,
        TelephoneNumberList $telephoneNumberList,
        GivenName $givenName = null,
        Surname $surname = null,
        Company $company = null
    ) {
        $this->contactType         = $contactType;
        $this->emailAddressList    = $emailAddressList;
        $this->telephoneNumberList = $telephoneNumberList;
        $this->givenName           = $givenName;
        $this->surname             = $surname;
        $this->company             = $company;
    }

    /**
     * @param ContactPerson $other
     * @return bool
     */
    public function equals(ContactPerson $other)
    {
        if (!$this->contactType->equals($other->contactType)) {
            return false;
        }

        if (!$this->emailAddressList->equals($other->emailAddressList)) {
            return false;
        }

        if (!$this->telephoneNumberList->equals($other->telephoneNumberList)) {
            return false;
        }

        if ($this->givenName != $other->givenName) {
            return false;
        }

        if ($this->surname != $other->surname) {
            return false;
        }

        if ($this->company != $other->company) {
            return false;
        }

        return true;
    }

    /**
     * @return ContactType
     */
    public function getContactType()
    {
        return $this->contactType;
    }

    /**
     * @return EmailAddressList
     */
    public function getEmailAddressList()
    {
        return $this->emailAddressList;
    }

    /**
     * @return TelephoneNumberList
     */
    public function getTelephoneNumberList()
    {
        return $this->telephoneNumberList;
    }

    /**
     * @return null|GivenName
     */
    public function getGivenName()
    {
        return $this->givenName;
    }

    /**
     * @return null|Surname
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @return null|Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    public static function deserialize($data)
    {
        Assertion::isArray($data);
        Assertion::keysExist(
            $data,
            array('contact_type', 'email_address_list', 'telephone_number_list', 'given_name', 'surname', 'company')
        );

        $contact = new self(
            ContactType::deserialize($data['contact_type']),
            EmailAddressList::deserialize($data['email_address_list']),
            TelephoneNumberList::deserialize($data['telephone_number_list'])
        );

        if ($data['given_name']) {
            $contact->givenName = GivenName::deserialize($data['given_name']);
        }

        if ($data['surname']) {
            $contact->surname = Surname::deserialize($data['surname']);
        }

        if ($data['company']) {
            $contact->company = Company::deserialize($data['company']);
        }

        return $contact;
    }

    public function serialize()
    {
        return array(
            'contact_type'          => $this->contactType->serialize(),
            'email_address_list'    => $this->emailAddressList->serialize(),
            'telephone_number_list' => $this->telephoneNumberList->serialize(),
            'given_name'            => ($this->givenName ? $this->givenName->serialize() : null),
            'surname'               => ($this->surname ? $this->surname->serialize() : null),
            'company'               => ($this->company ? $this->company->serialize() : null)
        );
    }

    public function __toString()
    {
        return sprintf(
            'ContactPerson(%s, =%s, %s%s%s%s)',
            'ContactType=' . $this->contactType,
            'EmailAddressList=' . $this->emailAddressList,
            'TelephoneNumberList=' . $this->telephoneNumberList,
            ($this->givenName ? ', GivenName=' . $this->givenName : ''),
            ($this->surname ? ', Surname=' . $this->surname : ''),
            ($this->company ? ', Company=' . $this->company : '')
        );
    }
}
