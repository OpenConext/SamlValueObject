<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;
use stdClass;

class EmailAddressListTest extends UnitTest
{
    /**
     * @test
     * @group metadata
     * @group contactperson
     *
     * @expectedException InvalidArgumentException
     */
    public function all_elements_must_be_an_email_address()
    {
        $invalidElements = array(
            new EmailAddress('homer@domain.invalid'),
            new EmailAddress('marge@domain.invalid'),
            new stdClass()
        );

        new EmailAddressList($invalidElements);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function adding_an_email_address_returns_a_new_list_with_that_email_address_appended()
    {
        $initialEmailOne = new EmailAddress('homer@domain.invalid');
        $initialEmailTwo = new EmailAddress('marge@domain.invalid');
        $additionalEmail = new EmailAddress('john.doe@domain.invalid');

        $list = new EmailAddressList(array($initialEmailOne, $initialEmailTwo));

        $newList = $list->add($additionalEmail);

        $this->assertNotSame($newList, $list, 'when adding an element to a list a new list must be returned');

        $this->assertTrue($list->contains($initialEmailOne));
        $this->assertTrue($list->contains($initialEmailTwo));
        $this->assertFalse($list->contains($additionalEmail));

        $this->assertTrue($newList->contains($initialEmailOne));
        $this->assertTrue($newList->contains($initialEmailTwo));
        $this->assertTrue($newList->contains($additionalEmail));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function presence_of_an_email_address_can_be_tested()
    {
        $emailOne  = new EmailAddress('homer@domain.invalid');
        $emailTwo  = new EmailAddress('marge@domain.invalid');
        $notInList = new EmailAddress('john.doe@domain.invalid');

        $list = new EmailAddressList(array($emailOne, $emailTwo));

        $this->assertTrue($list->contains($emailOne));
        $this->assertTrue($list->contains($emailTwo));
        $this->assertFalse($list->contains($notInList));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function the_index_of_an_email_address_can_be_retrieved()
    {
        $emailOne  = new EmailAddress('homer@domain.invalid');
        $emailTwo  = new EmailAddress('marge@domain.invalid');
        $notInList = new EmailAddress('john.doe@domain.invalid');

        $list = new EmailAddressList(array($emailOne, $emailTwo));

        $this->assertEquals(0, $list->indexOf($emailOne));
        $this->assertEquals(1, $list->indexOf($emailTwo));
        $this->assertEquals(-1, $list->indexOf($notInList), 'An element not in the list must have an index of -1');
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function an_email_address_can_be_retrieved_by_index()
    {
        $emailOne = new EmailAddress('homer@domain.invalid');
        $emailTwo = new EmailAddress('marge@domain.invalid');

        $list = new EmailAddressList(array($emailOne, $emailTwo));

        $this->assertSame($emailOne, $list->get(0));
        $this->assertSame($emailTwo, $list->get(1));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notInteger
     * @expectedException InvalidArgumentException
     */
    public function index_to_retrieve_the_element_of_must_be_an_integer($invalidArgument)
    {
        $emailOne = new EmailAddress('homer@domain.invalid');
        $emailTwo = new EmailAddress('marge@domain.invalid');

        $list = new EmailAddressList(array($emailOne, $emailTwo));

        $list->get($invalidArgument);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     *
     * @expectedException \OpenConext\Value\Exception\IndexOutOfBoundsException
     */
    public function an_exception_is_thrown_when_attempting_to_get_an_element_with_a_negative_index()
    {
        $emailOne = new EmailAddress('homer@domain.invalid');
        $emailTwo = new EmailAddress('marge@domain.invalid');

        $list = new EmailAddressList(array($emailOne, $emailTwo));

        $list->get(-1);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     *
     * @expectedException \OpenConext\Value\Exception\IndexOutOfBoundsException
     */
    public function an_exception_is_thrown_when_attempting_to_get_an_element_with_an_index_larger_than_the_list_size()
    {
        $emailOne = new EmailAddress('homer@domain.invalid');
        $emailTwo = new EmailAddress('marge@domain.invalid');

        $list = new EmailAddressList(array($emailOne, $emailTwo));

        $list->get(4);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function an_email_address_can_be_searched_for()
    {
        $predicate = function (EmailAddress $address) {
            return strpos($address, 'marge') === 0;
        };

        $homer = new EmailAddress('homer@domain.invalid');
        $marge = new EmailAddress('marge@domain.invalid');

        $list = new EmailAddressList(array($homer, $marge));

        $this->assertSame($marge, $list->find($predicate));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function find_returns_the_first_matching_element()
    {
        $predicate = function (EmailAddress $address) {
            return strpos($address, 'marge') === 0;
        };

        $emailOne    = new EmailAddress('homer@domain.invalid');
        $emailTwo    = new EmailAddress('marge@domain.invalid');
        $notReturned = new EmailAddress('marge@domain.invalid');

        $list = new EmailAddressList(array($emailOne, $emailTwo, $notReturned));

        $this->assertSame($emailTwo, $list->find($predicate));
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

        $emailOne = new EmailAddress('homer@domain.invalid');
        $emailTwo = new EmailAddress('marge@domain.invalid');

        $list = new EmailAddressList(array($emailOne, $emailTwo));

        $this->assertNull($list->find($predicate));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notCallable
     * @expectedException InvalidArgumentException
     *
     * @param mixed $notCallable
     */
    public function find_predicate_must_be_a_callable($notCallable)
    {
        $emailOne = new EmailAddress('homer@domain.invalid');
        $emailTwo = new EmailAddress('marge@domain.invalid');

        $list = new EmailAddressList(array($emailOne, $emailTwo));

        $list->find($notCallable);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function lists_are_only_equal_when_containing_the_same_elements_in_the_same_order()
    {
        $emailOne   = new EmailAddress('homer@domain.invalid');
        $emailTwo   = new EmailAddress('marge@domain.invalid');
        $emailThree = new EmailAddress('john.doe@domain.invalid');

        $base           = new EmailAddressList(array($emailOne, $emailTwo));
        $theSame        = new EmailAddressList(array($emailOne, $emailTwo));
        $differentOrder = new EmailAddressList(array($emailTwo, $emailOne));
        $lessElements   = new EmailAddressList(array($emailOne));
        $moreElements   = new EmailAddressList(array($emailOne, $emailTwo, $emailThree));

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
    public function an_email_address_list_can_be_iterated_over()
    {
        $emailOne = new EmailAddress('homer@domain.invalid');
        $emailTwo = new EmailAddress('marge@domain.invalid');

        $list = new EmailAddressList(array($emailOne, $emailTwo));

        $unknownElementSeen = false;
        $emailOneSeen = $emailTwoSeen = false;

        foreach ($list as $emailAddress) {
            if (!$emailOneSeen && $emailAddress === $emailOne) {
                $emailOneSeen = true;
            } elseif (!$emailTwoSeen && $emailAddress === $emailTwo) {
                $emailTwoSeen = true;
            } else {
                $unknownElementSeen = true;
            }
        }

        $this->assertFalse($unknownElementSeen, 'Found unknown element while iterating over EmailAddressList');
        $this->assertTrue($emailOneSeen, 'Missing expected element emailOne when iterating over EmailAddressList');
        $this->assertTrue($emailTwoSeen, 'Missing expected element emailTwo when iterating over EmailAddressList');
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function the_elements_in_an_email_address_list_can_be_counted()
    {
        $numberOne   = new EmailAddress('homer@domain.invalid');
        $numberTwo   = new EmailAddress('marge@domain.invalid');
        $numberThree = new EmailAddress('john.doe@domain.invalid');

        $twoElements   = new EmailAddressList(array($numberThree, $numberTwo));
        $threeElements = new EmailAddressList(array($numberThree, $numberTwo, $numberOne));

        $this->assertCount(2, $twoElements);
        $this->assertCount(3, $threeElements);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function a_list_exposes_an_array_containing_its_elements()
    {
        $numberOne = new EmailAddress('homer@domain.invalid');
        $numberTwo = new EmailAddress('marge@domain.invalid');

        $list = new EmailAddressList(array($numberTwo, $numberOne, $numberTwo));

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
    public function deserializing_a_serialized_email_address_list_results_in_an_equal_value_object()
    {
        $emailOne = new EmailAddress('homer@domain.invalid');
        $emailTwo = new EmailAddress('marge@domain.invalid');

        $original     = new EmailAddressList(array($emailOne, $emailTwo));
        $deserialized = EmailAddressList::deserialize($original->serialize());

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
    public function deserialization_requires_an_array($notArray)
    {
        EmailAddressList::deserialize($notArray);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function an_email_address_list_can_be_cast_to_string()
    {
        $numberOne = new EmailAddress('homer@domain.invalid');
        $numberTwo = new EmailAddress('marge@domain.invalid');

        $list = new EmailAddressList(array($numberOne, $numberTwo));

        $string = sprintf('EmailAddressList[%s, %s]', (string) $numberOne, (string) $numberTwo);
        $this->assertSame($string, (string) $list);
    }
}
