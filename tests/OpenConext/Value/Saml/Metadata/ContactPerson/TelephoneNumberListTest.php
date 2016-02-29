<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;

class TelephoneNumberListTest extends UnitTest
{
    /**
     * @test
     * @group metadata
     * @group contactperson
     *
     * @expectedException InvalidArgumentException
     */
    public function all_elements_must_be_a_telephone_number()
    {
        $invalidElements = array(new TelephoneNumber('123'), new TelephoneNumber('456'), new \stdClass());

        new TelephoneNumberList($invalidElements);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function adding_a_telephone_number_returns_a_new_list_with_that_number_appended()
    {
        $initialNumberOne = new TelephoneNumber('123');
        $initialNumberTwo = new TelephoneNumber('456');
        $additionalNumber = new TelephoneNumber('789');

        $list = new TelephoneNumberList(array($initialNumberOne, $initialNumberTwo));

        $newList = $list->add($additionalNumber);

        $this->assertNotSame($newList, $list, 'when adding an element to a list a new list must be returned');

        $this->assertTrue($list->contains($initialNumberOne));
        $this->assertTrue($list->contains($initialNumberTwo));
        $this->assertFalse($list->contains($additionalNumber));

        $this->assertTrue($newList->contains($initialNumberOne));
        $this->assertTrue($newList->contains($initialNumberTwo));
        $this->assertTrue($newList->contains($additionalNumber));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function presence_of_a_telephone_number_can_be_tested()
    {
        $numberOne = new TelephoneNumber('123');
        $numberTwo = new TelephoneNumber('456');
        $notInList = new TelephoneNumber('789');

        $list = new TelephoneNumberList(array($numberOne, $numberTwo));

        $this->assertTrue($list->contains($numberOne));
        $this->assertTrue($list->contains($numberTwo));
        $this->assertFalse($list->contains($notInList));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function the_index_of_a_telephone_number_can_be_retrieved()
    {
        $numberOne = new TelephoneNumber('123');
        $numberTwo = new TelephoneNumber('456');
        $notInList = new TelephoneNumber('789');

        $list = new TelephoneNumberList(array($numberOne, $numberTwo));

        $this->assertEquals(0, $list->indexOf($numberOne));
        $this->assertEquals(1, $list->indexOf($numberTwo));
        $this->assertEquals(-1, $list->indexOf($notInList), 'An element not in the list has an index of -1');
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function a_telephone_number_can_be_retrieved_by_index()
    {
        $numberOne = new TelephoneNumber('123');
        $numberTwo = new TelephoneNumber('456');

        $list = new TelephoneNumberList(array($numberOne, $numberTwo));

        $this->assertSame($numberOne, $list->get(0));
        $this->assertSame($numberTwo, $list->get(1));
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
        $numberOne = new TelephoneNumber('123');
        $numberTwo = new TelephoneNumber('456');

        $list = new TelephoneNumberList(array($numberOne, $numberTwo));

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
        $numberOne = new TelephoneNumber('123');
        $numberTwo = new TelephoneNumber('456');

        $list = new TelephoneNumberList(array($numberOne, $numberTwo));

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
        $numberOne = new TelephoneNumber('123');
        $numberTwo = new TelephoneNumber('456');

        $list = new TelephoneNumberList(array($numberOne, $numberTwo));

        $list->get(4);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function a_telephone_number_can_be_searched_for()
    {
        $predicate = function (TelephoneNumber $telephoneNumber) {
            return $telephoneNumber->getTelephoneNumber() === '456';
        };

        $numberOne = new TelephoneNumber('123');
        $numberTwo = new TelephoneNumber('456');

        $list = new TelephoneNumberList(array($numberOne, $numberTwo));

        $this->assertSame($numberTwo, $list->find($predicate));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function find_returns_the_first_matching_element()
    {
        $predicate = function (TelephoneNumber $telephoneNumber) {
            return $telephoneNumber->getTelephoneNumber() === '456';
        };

        $numberOne   = new TelephoneNumber('123');
        $numberTwo   = new TelephoneNumber('456');
        $notReturned = new TelephoneNumber('456');

        $list = new TelephoneNumberList(array($numberOne, $numberTwo, $notReturned));

        $this->assertSame($numberTwo, $list->find($predicate));
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

        $numberOne = new TelephoneNumber('123');
        $numberTwo = new TelephoneNumber('456');

        $list = new TelephoneNumberList(array($numberOne, $numberTwo));

        $this->assertNull($list->find($predicate));
    }

    /**
     * @test
     * @group        metadata
     * @group        contactperson
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notCallable
     * @expectedException InvalidArgumentException
     *
     * @param mixed $notCallable
     */
    public function find_predicate_must_be_a_callable($notCallable)
    {
        $numberOne = new TelephoneNumber('123');
        $numberTwo = new TelephoneNumber('456');

        $list = new TelephoneNumberList(array($numberOne, $numberTwo));

        $list->find($notCallable);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function lists_are_only_equal_when_containing_the_same_elements_in_the_same_order()
    {
        $numberOne   = new TelephoneNumber('123');
        $numberTwo   = new TelephoneNumber('456');
        $numberThree = new TelephoneNumber('789');

        $base           = new TelephoneNumberList(array($numberOne, $numberTwo));
        $theSame        = new TelephoneNumberList(array($numberOne, $numberTwo));
        $differentOrder = new TelephoneNumberList(array($numberTwo, $numberOne));
        $lessElements   = new TelephoneNumberList(array($numberOne));
        $moreElements   = new TelephoneNumberList(array($numberOne, $numberTwo, $numberThree));

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
    public function a_telephone_number_list_can_be_iterated_over()
    {
        $numberOne = new TelephoneNumber('123');
        $numberTwo = new TelephoneNumber('456');

        $list = new TelephoneNumberList(array($numberOne, $numberTwo));

        $unknownElementSeen = false;
        $numberOneSeen = $numberTwoSeen = false;

        foreach ($list as $telephoneNumber) {
            if (!$numberOneSeen && $telephoneNumber === $numberOne) {
                $numberOneSeen = true;
            } elseif (!$numberTwoSeen && $telephoneNumber === $numberTwo) {
                $numberTwoSeen = true;
            } else {
                $unknownElementSeen = true;
            }
        }

        $this->assertFalse($unknownElementSeen, 'Found unknown element while iterating over TelephoneNumberList');
        $this->assertTrue($numberOneSeen, 'Missing expected element numberOne when iterating over TelephoneNumberList');
        $this->assertTrue($numberTwoSeen, 'Missing expected element numberTwo when iterating over TelephoneNumberList');
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function the_elements_in_a_telephone_number_list_can_be_counted()
    {
        $numberOne   = new TelephoneNumber('123');
        $numberTwo   = new TelephoneNumber('456');
        $numberThree = new TelephoneNumber('789');

        $twoElements = new TelephoneNumberList(array($numberThree, $numberTwo));
        $threeElements = new TelephoneNumberList(array($numberThree, $numberTwo, $numberOne));

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
        $numberOne = new TelephoneNumber('123');
        $numberTwo = new TelephoneNumber('456');

        $list = new TelephoneNumberList(array($numberTwo, $numberOne, $numberTwo));

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
    public function deserializing_a_serialized_telephone_number_list_results_in_an_equal_value_object()
    {
        $numberOne = new TelephoneNumber('123');
        $numberTwo = new TelephoneNumber('456');

        $original     = new TelephoneNumberList(array($numberTwo, $numberOne, $numberTwo));
        $deserialized = TelephoneNumberList::deserialize($original->serialize());

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
        TelephoneNumberList::deserialize($notArray);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function a_telephone_number_list_can_be_cast_to_string()
    {
        $numberOne = new TelephoneNumber('123');
        $numberTwo = new TelephoneNumber('456');

        $list = new TelephoneNumberList(array($numberOne, $numberTwo));

        $string = sprintf('TelephoneNumberList[%s, %s]', (string) $numberOne, (string) $numberTwo);
        $this->assertSame($string, (string) $list);
    }
}
