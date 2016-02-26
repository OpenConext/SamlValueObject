<?php

namespace OpenConext\Value\Saml;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;
use stdClass;

class NameIdFormatListTest extends UnitTest
{
    /**
     * @test
     * @group nameid
     *
     * @expectedException InvalidArgumentException
     */
    public function all_elements_must_be_a_name_id_format()
    {
        $invalidElements = array(
            NameIdFormat::transient(),
            NameIdFormat::persistent(),
            new stdClass()
        );

        new NameIdFormatList($invalidElements);
    }

    /**
     * @test
     * @group nameid
     */
    public function adding_a_name_id_format_returns_a_new_list_with_that_name_id_format_appended()
    {
        $initialFormatOne = NameIdFormat::windowsDomainQualifiedName();
        $initialFormatTwo = NameIdFormat::unspecified();
        $additionalFormat = NameIdFormat::kerberosPrincipalName();

        $list = new NameIdFormatList(array($initialFormatOne, $initialFormatTwo));

        $newList = $list->add($additionalFormat);

        $this->assertNotSame($newList, $list, 'when adding an element to a list a new list must be returned');

        $this->assertTrue($list->contains($initialFormatOne));
        $this->assertTrue($list->contains($initialFormatTwo));
        $this->assertFalse($list->contains($additionalFormat));

        $this->assertTrue($newList->contains($initialFormatOne));
        $this->assertTrue($newList->contains($initialFormatTwo));
        $this->assertTrue($newList->contains($additionalFormat));
    }

    /**
     * @test
     * @group nameid
     */
    public function presence_of_a_name_id_format_can_be_tested()
    {
        $formatOne = NameIdFormat::transient();
        $formatTwo = NameIdFormat::persistent();
        $notInList = NameIdFormat::unspecified();

        $list = new NameIdFormatList(array($formatOne, $formatTwo));

        $this->assertTrue($list->contains($formatOne));
        $this->assertTrue($list->contains($formatTwo));
        $this->assertFalse($list->contains($notInList));
    }

    /**
     * @test
     * @group nameid
     */
    public function the_index_of_a_name_id_format_can_be_retrieved()
    {
        $formatOne = NameIdFormat::transient();
        $formatTwo = NameIdFormat::persistent();
        $notInList = NameIdFormat::unspecified();

        $list = new NameIdFormatList(array($formatOne, $formatTwo));

        $this->assertEquals(0, $list->indexOf($formatOne));
        $this->assertEquals(1, $list->indexOf($formatTwo));
        $this->assertEquals(-1, $list->indexOf($notInList), 'An element not in the list must have an index of -1');
    }

    /**
     * @test
     * @group nameid
     */
    public function a_name_id_format_can_be_retrieved_by_index()
    {
        $formatOne = NameIdFormat::transient();
        $formatTwo = NameIdFormat::unspecified();

        $list = new NameIdFormatList(array($formatOne, $formatTwo));

        $this->assertSame($formatOne, $list->get(0));
        $this->assertSame($formatTwo, $list->get(1));
    }

    /**
     * @test
     * @group nameid
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notInteger
     * @expectedException InvalidArgumentException
     *
     * @param mixed $invalidArgument
     */
    public function index_to_retrieve_the_element_of_must_be_an_integer($invalidArgument)
    {
        $formatOne = NameIdFormat::kerberosPrincipalName();
        $formatTwo = NameIdFormat::emailAddress();

        $list = new NameIdFormatList(array($formatOne, $formatTwo));

        $list->get($invalidArgument);
    }

    /**
     * @test
     * @group nameid
     *
     * @expectedException \OpenConext\Value\Exception\IndexOutOfBoundsException
     */
    public function an_exception_is_thrown_when_attempting_to_get_an_element_with_a_negative_index()
    {
        $formatOne = NameIdFormat::unspecified();
        $formatTwo = NameIdFormat::transient();

        $list = new NameIdFormatList(array($formatOne, $formatTwo));

        $list->get(-1);
    }

    /**
     * @test
     * @group nameid
     *
     * @expectedException \OpenConext\Value\Exception\IndexOutOfBoundsException
     */
    public function an_exception_is_thrown_when_attempting_to_get_an_element_with_an_index_larger_than_the_list_size()
    {
        $formatOne = NameIdFormat::unspecified();
        $formatTwo = NameIdFormat::transient();

        $list = new NameIdFormatList(array($formatOne, $formatTwo));

        $list->get(4);
    }

    /**
     * @test
     * @group nameid
     */
    public function lists_are_only_equal_when_containing_the_same_elements_in_the_same_order()
    {
        $formatOne   = NameIdFormat::transient();
        $formatTwo   = NameIdFormat::unspecified();
        $formatThree = NameIdFormat::persistent();

        $base           = new NameIdFormatList(array($formatOne, $formatTwo));
        $theSame        = new NameIdFormatList(array($formatOne, $formatTwo));
        $differentOrder = new NameIdFormatList(array($formatTwo, $formatOne));
        $lessElements   = new NameIdFormatList(array($formatOne));
        $moreElements   = new NameIdFormatList(array($formatOne, $formatTwo, $formatThree));

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($differentOrder));
        $this->assertFalse($base->equals($lessElements));
        $this->assertFalse($base->equals($moreElements));
    }

    /**
     * @test
     * @group nameid
     */
    public function a_name_id_format_list_can_be_iterated_over()
    {
        $formatOne = NameIdFormat::transient();
        $formatTwo = NameIdFormat::unspecified();

        $list = new NameIdFormatList(array($formatOne, $formatTwo));

        $unknownElementSeen = false;
        $formatOneSeen = $formatTwoSeen = false;

        foreach ($list as $format) {
            if (!$formatOneSeen && $format === $formatOne) {
                $formatOneSeen = true;
            } elseif (!$formatTwoSeen && $format === $formatTwo) {
                $formatTwoSeen = true;
            } else {
                $unknownElementSeen = true;
            }
        }

        $this->assertFalse($unknownElementSeen, 'Found unknown element while iterating over NameIdFormatList');
        $this->assertTrue($formatOneSeen, 'Missing expected element formatOne when iterating over NameIdFormatList');
        $this->assertTrue($formatTwoSeen, 'Missing expected element formatTwo when iterating over NameIdFormatList');
    }

    /**
     * @test
     * @group nameid
     */
    public function the_elements_in_a_name_id_format_list_can_be_counted()
    {
        $numberOne   = NameIdFormat::emailAddress();
        $numberTwo   = NameIdFormat::kerberosPrincipalName();
        $numberThree = NameIdFormat::windowsDomainQualifiedName();

        $twoElements   = new NameIdFormatList(array($numberThree, $numberTwo));
        $threeElements = new NameIdFormatList(array($numberThree, $numberTwo, $numberOne));

        $this->assertCount(2, $twoElements);
        $this->assertCount(3, $threeElements);
    }

    /**
     * @test
     * @group nameid
     */
    public function a_list_exposes_an_array_containing_its_elements()
    {
        $numberOne = NameIdFormat::emailAddress();
        $numberTwo = NameIdFormat::kerberosPrincipalName();

        $list = new NameIdFormatList(array($numberTwo, $numberOne, $numberTwo));

        $this->assertSame(
            array($numberTwo, $numberOne, $numberTwo),
            $list->toArray()
        );
    }

    /**
     * @test
     * @group nameid
     */
    public function deserializing_a_serialized_name_id_format_list_results_in_an_equal_value_object()
    {
        $formatOne = NameIdFormat::transient();
        $formatTwo = NameIdFormat::unspecified();

        $original     = new NameIdFormatList(array($formatOne, $formatTwo));
        $deserialized = NameIdFormatList::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     * @test
     * @group nameid
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notArray
     * @expectedException InvalidArgumentException
     *
     * @param mixed $notArray
     */
    public function deserialization_requires_an_array($notArray)
    {
        NameIdFormatList::deserialize($notArray);
    }

    /**
     * @test
     * @group nameid
     */
    public function a_name_id_format_list_can_be_cast_to_string()
    {
        $numberOne = NameIdFormat::emailAddress();
        $numberTwo = NameIdFormat::kerberosPrincipalName();

        $list = new NameIdFormatList(array($numberOne, $numberTwo));

        $string = sprintf('NameIdFormatList[%s, %s]', (string) $numberOne, (string) $numberTwo);
        $this->assertSame($string, (string) $list);
    }
}
