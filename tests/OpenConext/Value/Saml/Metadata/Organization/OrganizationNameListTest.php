<?php

namespace OpenConext\Value\Saml\Metadata\Organization;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;
use stdClass;

class OrganizationNameListTest extends UnitTest
{
    /**
     * @test
     * @group metadata
     * @group organization
     *
     * @expectedException InvalidArgumentException
     */
    public function all_elements_must_be_an_organization_name()
    {
        $invalidElements = array(
            new OrganizationName('OpenConext', 'en'),
            new OrganizationName('Different', 'en'),
            new stdClass()
        );

        new OrganizationNameList($invalidElements);
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function adding_an_organization_name_returns_a_new_list_with_that_name_appended()
    {
        $initialNameOne = new OrganizationName('OpenConext', 'en');
        $initialNameTwo = new OrganizationName('Different', 'en');
        $additionalName = new OrganizationName('SomeName', 'en');

        $list = new OrganizationNameList(array($initialNameOne, $initialNameTwo));

        $newList = $list->add($additionalName);

        $this->assertNotSame($newList, $list, 'when adding an element to a list a new list must be returned');

        $this->assertTrue($list->contains($initialNameOne));
        $this->assertTrue($list->contains($initialNameTwo));
        $this->assertFalse($list->contains($additionalName));

        $this->assertTrue($newList->contains($initialNameOne));
        $this->assertTrue($newList->contains($initialNameTwo));
        $this->assertTrue($newList->contains($additionalName));
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function presence_of_a_name_can_be_tested()
    {
        $nameOne   = new OrganizationName('OpenConext', 'en');
        $nameTwo   = new OrganizationName('Different', 'en');
        $notInList = new OrganizationName('SomeName', 'en');

        $list = new OrganizationNameList(array($nameOne, $nameTwo));

        $this->assertTrue($list->contains($nameOne));
        $this->assertTrue($list->contains($nameTwo));
        $this->assertFalse($list->contains($notInList));
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function the_index_of_an_organization_name_can_be_retrieved()
    {
        $nameOne   = new OrganizationName('OpenConext', 'en');
        $nameTwo   = new OrganizationName('Different', 'en');
        $notInList = new OrganizationName('SomeName', 'en');

        $list = new OrganizationNameList(array($nameOne, $nameTwo));

        $this->assertEquals(0, $list->indexOf($nameOne));
        $this->assertEquals(1, $list->indexOf($nameTwo));
        $this->assertEquals(-1, $list->indexOf($notInList), 'An element not in the list must have an index of -1');
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function an_organization_name_can_be_retrieved_by_index()
    {
        $nameOne = new OrganizationName('OpenConext', 'en');
        $nameTwo = new OrganizationName('Different', 'en');

        $list = new OrganizationNameList(array($nameOne, $nameTwo));

        $this->assertSame($nameOne, $list->get(0));
        $this->assertSame($nameTwo, $list->get(1));
    }

    /**
     * @test
     * @group metadata
     * @group organization
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notInteger
     * @expectedException InvalidArgumentException
     *
     * @param mixed $invalidArgument
     */
    public function index_to_retrieve_the_element_of_must_be_an_integer($invalidArgument)
    {
        $nameOne = new OrganizationName('OpenConext', 'en');
        $nameTwo = new OrganizationName('Different', 'en');

        $list = new OrganizationNameList(array($nameOne, $nameTwo));

        $list->get($invalidArgument);
    }

    /**
     * @test
     * @group metadata
     * @group organization
     *
     * @expectedException \OpenConext\Value\Exception\IndexOutOfBoundsException
     */
    public function an_exception_is_thrown_when_attempting_to_get_an_element_with_a_negative_index()
    {
        $nameOne = new OrganizationName('OpenConext', 'en');
        $nameTwo = new OrganizationName('Different', 'en');

        $list = new OrganizationNameList(array($nameOne, $nameTwo));

        $list->get(-1);
    }

    /**
     * @test
     * @group metadata
     * @group organization
     *
     * @expectedException \OpenConext\Value\Exception\IndexOutOfBoundsException
     */
    public function an_exception_is_thrown_when_attempting_to_get_an_element_with_an_index_larger_than_the_list_size()
    {
        $nameOne = new OrganizationName('OpenConext', 'en');
        $nameTwo = new OrganizationName('Different', 'en');

        $list = new OrganizationNameList(array($nameOne, $nameTwo));

        $list->get(4);
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function lists_are_only_equal_when_containing_the_same_elements_in_the_same_order()
    {
        $nameOne   = new OrganizationName('OpenConext', 'en');
        $nameTwo   = new OrganizationName('Different', 'en');
        $nameThree = new OrganizationName('SomeName', 'en');

        $base           = new OrganizationNameList(array($nameOne, $nameTwo));
        $theSame        = new OrganizationNameList(array($nameOne, $nameTwo));
        $differentOrder = new OrganizationNameList(array($nameTwo, $nameOne));
        $lessElements   = new OrganizationNameList(array($nameOne));
        $moreElements   = new OrganizationNameList(array($nameOne, $nameTwo, $nameThree));

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($differentOrder));
        $this->assertFalse($base->equals($lessElements));
        $this->assertFalse($base->equals($moreElements));
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function an_organization_name_list_can_be_iterated_over()
    {
        $nameOne = new OrganizationName('OpenConext', 'en');
        $nameTwo = new OrganizationName('Different', 'en');

        $list = new OrganizationNameList(array($nameOne, $nameTwo));

        $unknownElementSeen = false;
        $nameOneSeen = $nameTwoSeen = false;

        foreach ($list as $name) {
            if (!$nameOneSeen && $name === $nameOne) {
                $nameOneSeen = true;
            } elseif (!$nameTwoSeen && $name === $nameTwo) {
                $nameTwoSeen = true;
            } else {
                $unknownElementSeen = true;
            }
        }

        $this->assertFalse(
            $unknownElementSeen,
            'Found unknown element while iterating over OrganizationNameList'
        );
        $this->assertTrue(
            $nameOneSeen,
            'Missing expected element nameOne when iterating over OrganizationNameList'
        );
        $this->assertTrue(
            $nameTwoSeen,
            'Missing expected element nameTwo when iterating over OrganizationNameList'
        );
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function the_elements_in_an_organization_name_list_can_be_counted()
    {
        $numberOne   = new OrganizationName('OpenConext', 'en');
        $numberTwo   = new OrganizationName('Different', 'en');
        $numberThree = new OrganizationName('SomeName', 'en');

        $twoElements   = new OrganizationNameList(array($numberThree, $numberTwo));
        $threeElements = new OrganizationNameList(array($numberThree, $numberTwo, $numberOne));

        $this->assertCount(2, $twoElements);
        $this->assertCount(3, $threeElements);
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function a_list_exposes_an_array_containing_its_elements()
    {
        $numberOne = new OrganizationName('OpenConext', 'en');
        $numberTwo = new OrganizationName('Different', 'en');

        $list = new OrganizationNameList(array($numberTwo, $numberOne, $numberTwo));

        $this->assertSame(array($numberTwo, $numberOne, $numberTwo), $list->toArray());
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function deserializing_a_serialized_organization_name_list_results_in_an_equal_value_object()
    {
        $nameOne = new OrganizationName('OpenConext', 'en');
        $nameTwo = new OrganizationName('Different', 'en');

        $original     = new OrganizationNameList(array($nameOne, $nameTwo));
        $deserialized = OrganizationNameList::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     * @test
     * @group metadata
     * @group organization
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notArray
     * @expectedException InvalidArgumentException
     *
     * @param mixed $notArray
     */
    public function deserialization_requires_an_array($notArray)
    {
        OrganizationNameList::deserialize($notArray);
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function an_organization_name_list_can_be_cast_to_string()
    {
        $nameOne = new OrganizationName('OpenConext', 'en');
        $nameTwo = new OrganizationName('Different', 'en');

        $list = new OrganizationNameList(array($nameOne, $nameTwo));

        $string = sprintf('OrganizationNameList[%s, %s]', (string) $nameOne, (string) $nameTwo);
        $this->assertSame($string, (string) $list);
    }
}
