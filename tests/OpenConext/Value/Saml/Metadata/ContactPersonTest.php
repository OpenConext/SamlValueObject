<?php

namespace OpenConext\Value\Saml\Metadata;

use OpenConext\Value\Exception\InvalidArgumentException;
use OpenConext\Value\Saml\Metadata\ContactPerson\Company;
use OpenConext\Value\Saml\Metadata\ContactPerson\ContactType;
use OpenConext\Value\Saml\Metadata\ContactPerson\EmailAddress;
use OpenConext\Value\Saml\Metadata\ContactPerson\EmailAddressList;
use OpenConext\Value\Saml\Metadata\ContactPerson\GivenName;
use OpenConext\Value\Saml\Metadata\ContactPerson\Surname;
use OpenConext\Value\Saml\Metadata\ContactPerson\TelephoneNumber;
use OpenConext\Value\Saml\Metadata\ContactPerson\TelephoneNumberList;


class ContactPersonTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @group metadata
     * @group contactperson
     *
     * @dataProvider differentContactPersonProvider
     *
     * @param ContactPerson $differentContactPerson
     */
    public function contact_persons_are_compared_for_equality_on_all_properties($differentContactPerson)
    {
        $base = new ContactPerson(
            ContactType::technical(),
            new EmailAddressList(array(new EmailAddress('homer@domain.invalid'))),
            new TelephoneNumberList(array(new TelephoneNumber('123456'))),
            new GivenName('Homer'),
            new Surname('Simpson'),
            new Company('OpenConext.org')
        );

        $this->assertFalse($base->equals($differentContactPerson));
    }

    /**
     * @return array
     */
    public static function differentContactPersonProvider()
    {
        $type      = ContactType::technical();
        $email     = new EmailAddressList(array(new EmailAddress('homer@domain.invalid')));
        $telephone = new TelephoneNumberList(array(new TelephoneNumber('123456')));
        $givenName = new GivenName('Homer');
        $surname   = new Surname('Simpson');
        $company   = new Company('OpenConext.org');

        return array(
            'different type'       => array(new ContactPerson(ContactType::other(), $email, $telephone, $givenName, $surname, $company)),
            'different email'      => array(new ContactPerson($type, new EmailAddressList(array()), $telephone, $givenName, $surname, $company)),
            'different telephone'  => array(new ContactPerson($type, $email, new TelephoneNumberList(array()), $givenName, $surname, $company)),
            'different given name' => array(new ContactPerson($type, $email, $telephone, new GivenName('John'), $surname, $company)),
            'no given name'        => array(new ContactPerson($type, $email, $telephone, null, $surname, $company)),
            'different surname'    => array(new ContactPerson($type, $email, $telephone, $givenName, new Surname('Doe'), $company)),
            'no surname'           => array(new ContactPerson($type, $email, $telephone, $givenName, null, $company)),
            'different company'    => array(new ContactPerson($type, $email, $telephone, $givenName, $surname, new Company('Babelfish Inc.'))),
            'no company'           => array(new ContactPerson($type, $email, $telephone, $givenName, $surname, null)),
        );
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function contact_type_can_be_retrieved()
    {
        $type = ContactType::technical();
        $contactPerson = new ContactPerson(
            $type,
            new EmailAddressList(array(new EmailAddress('homer@domain.invalid'))),
            new TelephoneNumberList(array(new TelephoneNumber('123456')))
        );

        $this->assertSame($type, $contactPerson->getContactType());
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function email_address_list_can_be_retrieved()
    {
        $emailAddressList = new EmailAddressList(array(new EmailAddress('homer@domain.invalid')));
        $contactPerson = new ContactPerson(
            ContactType::technical(),
            $emailAddressList,
            new TelephoneNumberList(array(new TelephoneNumber('123456')))
        );

        $this->assertSame($emailAddressList, $contactPerson->getEmailAddressList());
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function telephone_number_list_can_be_retrieved()
    {
        $telephoneNumberList = new TelephoneNumberList(array(new TelephoneNumber('123456')));
        $contactPerson = new ContactPerson(
            ContactType::technical(),
            new EmailAddressList(array(new EmailAddress('homer@domain.invalid'))),
            $telephoneNumberList
        );

        $this->assertSame($telephoneNumberList, $contactPerson->getTelephoneNumberList());
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function given_name_can_be_retrieved()
    {
        $givenName = new GivenName('Homer');
        $contactPerson = new ContactPerson(
            ContactType::technical(),
            new EmailAddressList(array(new EmailAddress('homer@domain.invalid'))),
            new TelephoneNumberList(array(new TelephoneNumber('123456'))),
            $givenName,
            new Surname('Simpson'),
            new Company('OpenConext.org')
        );

        $this->assertSame($givenName, $contactPerson->getGivenName());
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function surname_can_be_retrieved()
    {
        $surname = new Surname('Simpson');
        $contactPerson = new ContactPerson(
            ContactType::technical(),
            new EmailAddressList(array(new EmailAddress('homer@domain.invalid'))),
            new TelephoneNumberList(array(new TelephoneNumber('123456'))),
            new GivenName('Homer'),
            $surname,
            new Company('OpenConext.org')
        );

        $this->assertSame($surname, $contactPerson->getSurname());
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function company_can_be_retrieved()
    {
        $company = new Company('OpenConext.org');
        $contactPerson = new ContactPerson(
            ContactType::technical(),
            new EmailAddressList(array(new EmailAddress('homer@domain.invalid'))),
            new TelephoneNumberList(array(new TelephoneNumber('123456'))),
            new GivenName('Homer'),
            new Surname('Simpson'),
            $company
        );

        $this->assertSame($company, $contactPerson->getCompany());
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function deserializing_a_serialized_contact_person_results_in_an_equal_value_object()
    {
        $original = new ContactPerson(
            ContactType::technical(),
            new EmailAddressList(array(new EmailAddress('homer@domain.invalid'))),
            new TelephoneNumberList(array(new TelephoneNumber('123456'))),
            new GivenName('Homer'),
            new Surname('Simpson'),
            new Company('OpenConext.org')
        );
        $deserialized = ContactPerson::deserialize($original->serialize());

        $this->assertTrue($deserialized->equals($original));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notArray
     *
     * @param mixed $notArray
     */
    public function deserialization_requires_an_array_as_data($notArray)
    {
        $this->expectException(\InvalidArgumentException::class);
        ContactPerson::deserialize($notArray);
    }

    /**
     * @test
     * @group        metadata
     * @group        contactperson
     *
     * @dataProvider invalidDeserializationDataProvider
     *
     * @param array $invalidData
     */
    public function deserialization_requires_data_with_expected_keys($invalidData)
    {
        $this->expectException(\InvalidArgumentException::class);
        ContactPerson::deserialize($invalidData);
    }

    /**
     * @return array
     */
    public static function invalidDeserializationDataProvider()
    {
        return array(
            'missing contact_type' => array(array_flip(array('email_address_list', 'telephone_number_list', 'given_name', 'surname', 'company'))),
            'missing email_address_list' => array(array_flip(array('contact_type', 'telephone_number_list', 'given_name', 'surname', 'company'))),
            'missing telephone_number_list' => array(array_flip(array('contact_type', 'email_address_list', 'given_name', 'surname', 'company'))),
            'missing given_name' => array(array_flip(array('contact_type', 'email_address_list', 'telephone_number_list', 'surname', 'company'))),
            'missing surname' => array(array_flip(array('contact_type', 'email_address_list', 'telephone_number_list', 'given_name', 'company'))),
            'missing company' => array(array_flip(array('contact_tpye', 'email_address_list', 'telephone_number_list', 'given_name', 'surname'))),
        );
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function a_contact_person_can_be_cast_to_string()
    {
        $contactPerson = new ContactPerson(
            ContactType::technical(),
            new EmailAddressList(array(new EmailAddress('homer@domain.invalid'))),
            new TelephoneNumberList(array(new TelephoneNumber('123456'))),
            new GivenName('Homer'),
            new Surname('Simpson'),
            new Company('OpenConext.org')
        );

        $this->assertStringStartsWith('ContactPerson', (string) $contactPerson);
    }
}
