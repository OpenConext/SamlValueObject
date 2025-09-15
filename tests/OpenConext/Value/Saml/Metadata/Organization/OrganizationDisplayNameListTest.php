<?php

namespace OpenConext\Value\Saml\Metadata\Organization;

use OpenConext\Value\Exception\InvalidArgumentException;

use stdClass;

class OrganizationDisplayNameListTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function all_elements_must_be_an_organization_display_name()
    {
        $this->expectException(\InvalidArgumentException::class);
        $invalidElements = array(
            new OrganizationDisplayName('OpenConext', 'en'),
            new OrganizationDisplayName('Different', 'en'),
            new stdClass()
        );

        new OrganizationDisplayNameList($invalidElements);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function adding_an_organization_display_name_returns_a_new_list_with_that_name_appended()
    {
        $initialNameOne = new OrganizationDisplayName('OpenConext', 'en');
        $initialNameTwo = new OrganizationDisplayName('Different', 'en');
        $additionalName = new OrganizationDisplayName('Display_Name', 'en');

        $list = new OrganizationDisplayNameList(array($initialNameOne, $initialNameTwo));

        $newList = $list->add($additionalName);

        $this->assertNotSame($newList, $list, 'when adding an element to a list a new list must be returned');

        $this->assertTrue($list->contains($initialNameOne));
        $this->assertTrue($list->contains($initialNameTwo));
        $this->assertFalse($list->contains($additionalName));

        $this->assertTrue($newList->contains($initialNameOne));
        $this->assertTrue($newList->contains($initialNameTwo));
        $this->assertTrue($newList->contains($additionalName));
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function presence_of_a_name_can_be_tested()
    {
        $displayNameOne   = new OrganizationDisplayName('OpenConext', 'en');
        $displayNameTwo   = new OrganizationDisplayName('Different', 'en');
        $notInList = new OrganizationDisplayName('Display_Name', 'en');

        $list = new OrganizationDisplayNameList(array($displayNameOne, $displayNameTwo));

        $this->assertTrue($list->contains($displayNameOne));
        $this->assertTrue($list->contains($displayNameTwo));
        $this->assertFalse($list->contains($notInList));
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_index_of_an_organization_display_name_can_be_retrieved()
    {
        $displayNameOne   = new OrganizationDisplayName('OpenConext', 'en');
        $displayNameTwo   = new OrganizationDisplayName('Different', 'en');
        $notInList = new OrganizationDisplayName('Display_Name', 'en');

        $list = new OrganizationDisplayNameList(array($displayNameOne, $displayNameTwo));

        $this->assertEquals(0, $list->indexOf($displayNameOne));
        $this->assertEquals(1, $list->indexOf($displayNameTwo));
        $this->assertEquals(-1, $list->indexOf($notInList), 'An element not in the list must have an index of -1');
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function an_organization_display_name_can_be_retrieved_by_index()
    {
        $displayNameOne = new OrganizationDisplayName('OpenConext', 'en');
        $displayNameTwo = new OrganizationDisplayName('Different', 'en');

        $list = new OrganizationDisplayNameList(array($displayNameOne, $displayNameTwo));

        $this->assertSame($displayNameOne, $list->get(0));
        $this->assertSame($displayNameTwo, $list->get(1));
    }

    /**
     *
     *
     * @param mixed $invalidArgument
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notInteger')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function index_to_retrieve_the_element_of_must_be_an_integer($invalidArgument)
    {
        $this->expectException(\InvalidArgumentException::class);
        $displayNameOne = new OrganizationDisplayName('OpenConext', 'en');
        $displayNameTwo = new OrganizationDisplayName('Different', 'en');

        $list = new OrganizationDisplayNameList(array($displayNameOne, $displayNameTwo));

        $list->get($invalidArgument);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function an_exception_is_thrown_when_attempting_to_get_an_element_with_a_negative_index()
    {
        $this->expectException(\OpenConext\Value\Exception\IndexOutOfBoundsException::class);
        $displayNameOne = new OrganizationDisplayName('OpenConext', 'en');
        $displayNameTwo = new OrganizationDisplayName('Different', 'en');

        $list = new OrganizationDisplayNameList(array($displayNameOne, $displayNameTwo));

        $list->get(-1);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function an_exception_is_thrown_when_attempting_to_get_an_element_with_an_index_larger_than_the_list_size()
    {
        $this->expectException(\OpenConext\Value\Exception\IndexOutOfBoundsException::class);
        $displayNameOne = new OrganizationDisplayName('OpenConext', 'en');
        $displayNameTwo = new OrganizationDisplayName('OpenConext', 'nl');

        $list = new OrganizationDisplayNameList(array($displayNameOne, $displayNameTwo));

        $list->get(4);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function an_organization_display_name_can_be_searched_for()
    {
        $predicate = function (OrganizationDisplayName $organizationDisplayName) {
            return $organizationDisplayName->getLanguage() === 'en_GB';
        };

        $displayNameOne = new OrganizationDisplayName('OpenConext', 'en_US');
        $displayNameTwo = new OrganizationDisplayName('OpenConext', 'en_GB');

        $list = new OrganizationDisplayNameList(array($displayNameOne, $displayNameTwo));

        $this->assertSame($displayNameTwo, $list->find($predicate));
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function find_returns_the_first_matching_element()
    {
        $predicate = function (OrganizationDisplayName $organizationDisplayName) {
            return $organizationDisplayName->getLanguage() === 'en_GB';
        };

        $displayNameOne = new OrganizationDisplayName('OpenConext', 'en_US');
        $displayNameTwo = new OrganizationDisplayName('OpenConext', 'en_GB');
        $notReturned    = new OrganizationDisplayName('OpenConext', 'en_GB');

        $list = new OrganizationDisplayNameList(array($displayNameOne, $displayNameTwo, $notReturned));

        $this->assertSame($displayNameTwo, $list->find($predicate));
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function null_is_returned_when_no_match_is_found()
    {
        $predicate = function () {
            return false;
        };

        $displayNameOne = new OrganizationDisplayName('OpenConext', 'en_US');
        $displayNameTwo = new OrganizationDisplayName('OpenConext', 'en_GB');

        $list = new OrganizationDisplayNameList(array($displayNameOne, $displayNameTwo));

        $this->assertNull($list->find($predicate));
    }

    /**
     *
     *
     * @param mixed $notCallable
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notCallable')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function find_predicate_must_be_a_callable($notCallable)
    {
        $this->expectException(\InvalidArgumentException::class);
        $displayNameOne = new OrganizationDisplayName('OpenConext', 'en_US');
        $displayNameTwo = new OrganizationDisplayName('OpenConext', 'en_GB');

        $list = new OrganizationDisplayNameList(array($displayNameOne, $displayNameTwo));

        $list->find($notCallable);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function lists_are_only_equal_when_containing_the_same_elements_in_the_same_order()
    {
        $displayNameOne   = new OrganizationDisplayName('OpenConext', 'en');
        $displayNameTwo   = new OrganizationDisplayName('Different', 'en');
        $displayNameThree = new OrganizationDisplayName('Display_Name', 'en');

        $base           = new OrganizationDisplayNameList(array($displayNameOne, $displayNameTwo));
        $theSame        = new OrganizationDisplayNameList(array($displayNameOne, $displayNameTwo));
        $differentOrder = new OrganizationDisplayNameList(array($displayNameTwo, $displayNameOne));
        $lessElements   = new OrganizationDisplayNameList(array($displayNameOne));
        $moreElements   = new OrganizationDisplayNameList(array($displayNameOne, $displayNameTwo, $displayNameThree));

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($differentOrder));
        $this->assertFalse($base->equals($lessElements));
        $this->assertFalse($base->equals($moreElements));
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function an_organization_display_name_list_can_be_iterated_over()
    {
        $displayNameOne = new OrganizationDisplayName('OpenConext', 'en');
        $displayNameTwo = new OrganizationDisplayName('Different', 'en');

        $list = new OrganizationDisplayNameList(array($displayNameOne, $displayNameTwo));

        $unknownElementSeen = false;
        $displayNameOneSeen = $displayNameTwoSeen = false;

        foreach ($list as $displayName) {
            if (!$displayNameOneSeen && $displayName === $displayNameOne) {
                $displayNameOneSeen = true;
            } elseif (!$displayNameTwoSeen && $displayName === $displayNameTwo) {
                $displayNameTwoSeen = true;
            } else {
                $unknownElementSeen = true;
            }
        }

        $this->assertFalse(
            $unknownElementSeen,
            'Found unknown element while iterating over OrganizationDisplayNameList'
        );
        $this->assertTrue(
            $displayNameOneSeen,
            'Missing expected element displayNameOne when iterating over OrganizationDisplayNameList'
        );
        $this->assertTrue(
            $displayNameTwoSeen,
            'Missing expected element displayNameTwo when iterating over OrganizationDisplayNameList'
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_elements_in_an_organization_display_name_list_can_be_counted()
    {
        $numberOne   = new OrganizationDisplayName('OpenConext', 'en');
        $numberTwo   = new OrganizationDisplayName('Different', 'en');
        $numberThree = new OrganizationDisplayName('Display_Name', 'en');

        $twoElements   = new OrganizationDisplayNameList(array($numberThree, $numberTwo));
        $threeElements = new OrganizationDisplayNameList(array($numberThree, $numberTwo, $numberOne));

        $this->assertCount(2, $twoElements);
        $this->assertCount(3, $threeElements);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function a_list_exposes_an_array_containing_its_elements()
    {
        $numberOne = new OrganizationDisplayName('OpenConext', 'en');
        $numberTwo = new OrganizationDisplayName('Different', 'en');

        $list = new OrganizationDisplayNameList(array($numberTwo, $numberOne, $numberTwo));

        $this->assertSame(
            array($numberTwo, $numberOne, $numberTwo),
            $list->toArray()
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserializing_a_serialized_organization_display_name_list_results_in_an_equal_value_object()
    {
        $displayNameOne = new OrganizationDisplayName('OpenConext', 'en');
        $displayNameTwo = new OrganizationDisplayName('Different', 'en');

        $original     = new OrganizationDisplayNameList(array($displayNameOne, $displayNameTwo));
        $deserialized = OrganizationDisplayNameList::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     *
     *
     * @param mixed $notArray
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notArray')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserialization_requires_an_array($notArray)
    {
        $this->expectException(\InvalidArgumentException::class);
        OrganizationDisplayNameList::deserialize($notArray);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function an_organization_display_name_list_can_be_cast_to_string()
    {
        $displayNameOne = new OrganizationDisplayName('OpenConext', 'en');
        $displayNameTwo = new OrganizationDisplayName('Different', 'en');

        $list = new OrganizationDisplayNameList(array($displayNameOne, $displayNameTwo));

        $string = sprintf('OrganizationDisplayNameList[%s, %s]', (string) $displayNameOne, (string) $displayNameTwo);
        $this->assertSame($string, (string) $list);
    }
}
