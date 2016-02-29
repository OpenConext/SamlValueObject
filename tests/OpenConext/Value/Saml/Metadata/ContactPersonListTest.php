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
use PHPUnit_Framework_TestCase as UnitTest;
use stdClass;

class ContactPersonListTest extends UnitTest
{
    /**
     * @test
     * @group metadata
     * @group contactperson
     *
     * @expectedException InvalidArgumentException
     */
    public function all_elements_must_be_a_contact_person()
    {
        $invalidElements = array(
            $this->getHomerContact(),
            $this->getMargeContact(),
            new stdClass()
        );

        new ContactPersonList($invalidElements);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function adding_a_contact_person_returns_a_new_list_with_that_contact_person_appended()
    {
        $initialPersonOne = $this->getHomerContact();
        $initialPersonTwo = $this->getMargeContact();
        $additionalPerson = $this->getJohnContact();

        $list = new ContactPersonList(array($initialPersonOne, $initialPersonTwo));

        $newList = $list->add($additionalPerson);

        $this->assertNotSame($newList, $list, 'when adding an element to a list a new list must be returned');

        $this->assertTrue($list->contains($initialPersonOne));
        $this->assertTrue($list->contains($initialPersonTwo));
        $this->assertFalse($list->contains($additionalPerson));

        $this->assertTrue($newList->contains($initialPersonOne));
        $this->assertTrue($newList->contains($initialPersonTwo));
        $this->assertTrue($newList->contains($additionalPerson));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function presence_of_a_contact_person_can_be_tested()
    {
        $personOne = $this->getHomerContact();
        $personTwo = $this->getMargeContact();
        $notInList = $this->getJohnContact();

        $list = new ContactPersonList(array($personOne, $personTwo));

        $this->assertTrue($list->contains($personOne));
        $this->assertTrue($list->contains($personTwo));
        $this->assertFalse($list->contains($notInList));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function the_index_of_a_contact_person_can_be_retrieved()
    {
        $personOne = $this->getHomerContact();
        $personTwo = $this->getMargeContact();
        $notInList = $this->getJohnContact();

        $list = new ContactPersonList(array($personOne, $personTwo));

        $this->assertEquals(0, $list->indexOf($personOne));
        $this->assertEquals(1, $list->indexOf($personTwo));
        $this->assertEquals(-1, $list->indexOf($notInList), 'An element not in the list must have an index of -1');
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function a_contact_person_can_be_retrieved_by_index()
    {
        $personOne = $this->getHomerContact();
        $personTwo = $this->getMargeContact();

        $list = new ContactPersonList(array($personOne, $personTwo));

        $this->assertSame($personOne, $list->get(0));
        $this->assertSame($personTwo, $list->get(1));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notInteger
     * @expectedException InvalidArgumentException
     */
    public function index_to_retrieve_the_element_of_must_be_a_integer($invalidArgument)
    {
        $personOne = $this->getHomerContact();
        $personTwo = $this->getMargeContact();

        $list = new ContactPersonList(array($personOne, $personTwo));

        $list->get($invalidArgument);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     *
     * @expectedException \OpenConext\Value\Exception\IndexOutOfBoundsException
     */
    public function an_exception_is_thrown_when_attempting_to_get_a_element_with_a_negative_index()
    {
        $personOne = $this->getHomerContact();
        $personTwo = $this->getMargeContact();

        $list = new ContactPersonList(array($personOne, $personTwo));

        $list->get(-1);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     *
     * @expectedException \OpenConext\Value\Exception\IndexOutOfBoundsException
     */
    public function an_exception_is_thrown_when_attempting_to_get_a_element_with_a_index_larger_than_the_list_size()
    {
        $personOne = $this->getHomerContact();
        $personTwo = $this->getMargeContact();

        $list = new ContactPersonList(array($personOne, $personTwo));

        $list->get(4);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function a_contact_person_can_be_searched_for()
    {
        $marge     = $this->getMargeContact();
        $predicate = function (ContactPerson $person) use ($marge) {
            return $person->equals($marge);
        };

        $personOne = $this->getHomerContact();
        $personTwo = $this->getMargeContact();

        $list = new ContactPersonList(array($personOne, $personTwo));

        $this->assertSame($personTwo, $list->find($predicate));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function find_returns_the_first_matching_element()
    {
        $marge = $this->getMargeContact();
        $predicate = function (ContactPerson $person) use ($marge) {
            return $person->equals($marge);
        };

        $personOne   = $this->getHomerContact();
        $personTwo   = $this->getMargeContact();
        $notReturned = $this->getMargeContact();

        $list = new ContactPersonList(array($personOne, $personTwo, $notReturned));

        $this->assertSame($personTwo, $list->find($predicate));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function null_is_returned_when_no_match_is_found()
    {
        $predicate = function () {
            return false;
        };

        $personOne = $this->getHomerContact();
        $personTwo = $this->getMargeContact();

        $list = new ContactPersonList(array($personOne, $personTwo));

        $this->assertNull($list->find($predicate));
    }

    /**
     * @test
     * @group        metadata
     * @group        organization
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notCallable
     * @expectedException InvalidArgumentException
     *
     * @param mixed $notCallable
     */
    public function find_predicate_must_be_a_callable($notCallable)
    {
        $personOne = $this->getHomerContact();
        $personTwo = $this->getMargeContact();

        $list = new ContactPersonList(array($personOne, $personTwo));

        $list->find($notCallable);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function lists_are_only_equal_when_containing_the_same_elements_in_the_same_order()
    {
        $personOne   = $this->getHomerContact();
        $personTwo   = $this->getMargeContact();
        $personThree = $this->getJohnContact();

        $base           = new ContactPersonList(array($personOne, $personTwo));
        $theSame        = new ContactPersonList(array($personOne, $personTwo));
        $differentOrder = new ContactPersonList(array($personTwo, $personOne));
        $lessElements   = new ContactPersonList(array($personOne));
        $moreElements   = new ContactPersonList(array($personOne, $personTwo, $personThree));

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($differentOrder));
        $this->assertFalse($base->equals($lessElements));
        $this->assertFalse($base->equals($moreElements));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function a_contact_person_list_can_be_iterated_over()
    {
        $personOne = $this->getHomerContact();
        $personTwo = $this->getMargeContact();

        $list = new ContactPersonList(array($personOne, $personTwo));

        $unknownElementSeen = false;
        $personOneSeen = $personTwoSeen = false;

        foreach ($list as $personAddress) {
            if (!$personOneSeen && $personAddress === $personOne) {
                $personOneSeen = true;
            } elseif (!$personTwoSeen && $personAddress === $personTwo) {
                $personTwoSeen = true;
            } else {
                $unknownElementSeen = true;
            }
        }

        $this->assertFalse($unknownElementSeen, 'Found unknown element while iterating over ContactPersonList');
        $this->assertTrue($personOneSeen, 'Missing expected element emailOne when iterating over ContactPersonList');
        $this->assertTrue($personTwoSeen, 'Missing expected element emailTwo when iterating over ContactPersonList');
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function the_elements_in_a_contact_person_list_can_be_counted()
    {
        $numberOne   = $this->getHomerContact();
        $numberTwo   = $this->getMargeContact();
        $numberThree = $this->getJohnContact();

        $twoElements   = new ContactPersonList(array($numberThree, $numberTwo));
        $threeElements = new ContactPersonList(array($numberThree, $numberTwo, $numberOne));

        $this->assertCount(2, $twoElements);
        $this->assertCount(3, $threeElements);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function a_list_exposes_a_array_containing_its_elements()
    {
        $numberOne = $this->getHomerContact();
        $numberTwo = $this->getMargeContact();

        $list = new ContactPersonList(array($numberTwo, $numberOne, $numberTwo));

        $this->assertSame(
            array($numberTwo, $numberOne, $numberTwo),
            $list->toArray()
        );
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function deserializing_a_serialized_contact_person_list_results_in_a_equal_value_object()
    {
        $personOne = $this->getHomerContact();
        $personTwo = $this->getMargeContact();

        $original     = new ContactPersonList(array($personOne, $personTwo));
        $deserialized = ContactPersonList::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     * @test
     * @group        metadata
     * @group        contactperson
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notArray
     * @expectedException InvalidArgumentException
     *
     * @param mixed $notArray
     */
    public function deserialization_requires_a_array($notArray)
    {
        ContactPersonList::deserialize($notArray);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function a_contact_person_list_can_be_cast_to_string()
    {
        $numberOne = $this->getHomerContact();
        $numberTwo = $this->getMargeContact();

        $list = new ContactPersonList(array($numberOne, $numberTwo));

        $this->assertTrue(is_string((string) $list));
    }

    /**
     * @return ContactPerson
     */
    private function getHomerContact()
    {
        return new ContactPerson(
            ContactType::technical(),
            new EmailAddressList(array(new EmailAddress('homer@domain.invalid'))),
            new TelephoneNumberList(array(new TelephoneNumber('123456'))),
            new GivenName('Homer'),
            new Surname('Simpson'),
            new Company('OpenConext.org')
        );
    }

    /**
     * @return ContactPerson
     */
    private function getMargeContact()
    {
        return new ContactPerson(
            ContactType::technical(),
            new EmailAddressList(array(new EmailAddress('homer@domain.invalid'))),
            new TelephoneNumberList(array(new TelephoneNumber('098765'))),
            new GivenName('Marge'),
            new Surname('Simpson'),
            new Company('OpenConext.org')
        );
    }

    /**
     * @return ContactPerson
     */
    private function getJohnContact()
    {
        return new ContactPerson(
            ContactType::technical(),
            new EmailAddressList(array(new EmailAddress('john.doe@domain.invalid'))),
            new TelephoneNumberList(array(new TelephoneNumber('123987'))),
            new GivenName('John'),
            new Surname('Doe'),
            new Company('OpenConext.org')
        );
    }
}
