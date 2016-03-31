<?php

namespace OpenConext\Value\Saml\Metadata\Organization;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;
use stdClass;

class OrganizationUrlListTest extends UnitTest
{
    /**
     * @test
     * @group metadata
     * @group organization
     *
     * @expectedException InvalidArgumentException
     */
    public function all_elements_must_be_an_organization_irl()
    {
        $invalidElements = array(
            new OrganizationUrl('https://www.openconext.org', 'en'),
            new OrganizationUrl('https://www.domain.invalid', 'en'),
            new stdClass()
        );

        new OrganizationUrlList($invalidElements);
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function adding_an_organization_url_returns_a_new_list_with_that_name_appended()
    {
        $initialUrlOne = new OrganizationUrl('https://www.openconext.org', 'en');
        $initialUrlTwo = new OrganizationUrl('https://www.domain.invalid', 'en');
        $additionalUrl = new OrganizationUrl('https://www.google.com', 'en');

        $list = new OrganizationUrlList(array($initialUrlOne, $initialUrlTwo));
        $newList = $list->add($additionalUrl);

        $this->assertNotSame($newList, $list, 'when adding an element to a list a new list must be returned');

        $this->assertTrue($list->contains($initialUrlOne));
        $this->assertTrue($list->contains($initialUrlTwo));
        $this->assertFalse($list->contains($additionalUrl));

        $this->assertTrue($newList->contains($initialUrlOne));
        $this->assertTrue($newList->contains($initialUrlTwo));
        $this->assertTrue($newList->contains($additionalUrl));
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function presence_of_an_organization_url_can_be_tested()
    {
        $urlOne    = new OrganizationUrl('https://www.openconext.org', 'en');
        $urlTwo    = new OrganizationUrl('https://www.domain.invalid', 'en');
        $notInList = new OrganizationUrl('https://www.google.com', 'en');

        $list = new OrganizationUrlList(array($urlOne, $urlTwo));

        $this->assertTrue($list->contains($urlOne));
        $this->assertTrue($list->contains($urlTwo));
        $this->assertFalse($list->contains($notInList));
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function the_index_of_an_organization_url_can_be_retrieved()
    {
        $urlOne    = new OrganizationUrl('https://www.openconext.org', 'en');
        $urlTwo    = new OrganizationUrl('https://www.domain.invalid', 'en');
        $notInList = new OrganizationUrl('https://www.google.com', 'en');

        $list = new OrganizationUrlList(array($urlOne, $urlTwo));

        $this->assertEquals(0, $list->indexOf($urlOne));
        $this->assertEquals(1, $list->indexOf($urlTwo));
        $this->assertEquals(-1, $list->indexOf($notInList), 'An element not in the list must have an index of -1');
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function an_organization_url_can_be_retrieved_by_index()
    {
        $urlOne = new OrganizationUrl('https://www.openconext.org', 'en');
        $urlTwo = new OrganizationUrl('https://www.domain.invalid', 'en');

        $list = new OrganizationUrlList(array($urlOne, $urlTwo));

        $this->assertSame($urlOne, $list->get(0));
        $this->assertSame($urlTwo, $list->get(1));
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
        $urlOne = new OrganizationUrl('https://www.openconext.org', 'en');
        $urlTwo = new OrganizationUrl('https://www.domain.invalid', 'en');

        $list = new OrganizationUrlList(array($urlOne, $urlTwo));

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
        $urlOne = new OrganizationUrl('https://www.openconext.org', 'en');
        $urlTwo = new OrganizationUrl('https://www.domain.invalid', 'en');

        $list = new OrganizationUrlList(array($urlOne, $urlTwo));

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
        $urlOne = new OrganizationUrl('https://www.openconext.org', 'en');
        $urlTwo = new OrganizationUrl('https://www.domain.invalid', 'en');

        $list = new OrganizationUrlList(array($urlOne, $urlTwo));

        $list->get(4);
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function an_organization_url_can_be_searched_for()
    {
        $predicate = function (OrganizationUrl $organizationUrl) {
            return $organizationUrl->getLanguage() === 'en_GB';
        };

        $urlOne = new OrganizationUrl('https://www.openconext.org', 'en_US');
        $urlTwo = new OrganizationUrl('https://www.openconext.org', 'en_GB');

        $list = new OrganizationUrlList(array($urlOne, $urlTwo));

        $this->assertSame($urlTwo, $list->find($predicate));
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function find_returns_the_first_matching_element()
    {
        $predicate = function (OrganizationUrl $organizationUrl) {
            return $organizationUrl->getUrl() === 'https://www.domain.invalid';
        };

        $urlOne      = new OrganizationUrl('https://www.openconext.org', 'en');
        $urlTwo      = new OrganizationUrl('https://www.domain.invalid', 'en');
        $notReturned = new OrganizationUrl('https://www.domain.invalid', 'en');

        $list = new OrganizationUrlList(array($urlOne, $urlTwo, $notReturned));

        $this->assertSame($urlTwo, $list->find($predicate));
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function null_is_returned_when_no_match_is_found()
    {
        $predicate = function () {
            return false;
        };

        $urlOne = new OrganizationUrl('https://www.openconext.org', 'en');
        $urlTwo = new OrganizationUrl('https://www.domain.invalid', 'en');

        $list = new OrganizationUrlList(array($urlOne, $urlTwo));

        $this->assertNull($list->find($predicate));
    }

    /**
     * @test
     * @group metadata
     * @group organization
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notCallable
     * @expectedException InvalidArgumentException
     *
     * @param mixed $notCallable
     */
    public function find_predicate_must_be_a_callable($notCallable)
    {
        $urlOne = new OrganizationUrl('OpenConext', 'en_US');
        $urlTwo = new OrganizationUrl('OpenConext', 'en_GB');

        $list = new OrganizationUrlList(array($urlOne, $urlTwo));

        $list->find($notCallable);
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function lists_are_only_equal_when_containing_the_same_elements_in_the_same_order()
    {
        $urlOne   = new OrganizationUrl('https://www.openconext.org', 'en');
        $urlTwo   = new OrganizationUrl('https://www.domain.invalid', 'en');
        $urlThree = new OrganizationUrl('https://www.google.com', 'en');

        $base           = new OrganizationUrlList(array($urlOne, $urlTwo));
        $theSame        = new OrganizationUrlList(array($urlOne, $urlTwo));
        $differentOrder = new OrganizationUrlList(array($urlTwo, $urlOne));
        $lessElements   = new OrganizationUrlList(array($urlOne));
        $moreElements   = new OrganizationUrlList(array($urlOne, $urlTwo, $urlThree));

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
    public function an_organization_url_list_can_be_iterated_over()
    {
        $urlOne = new OrganizationUrl('https://www.openconext.org', 'en');
        $urlTwo = new OrganizationUrl('https://www.domain.invalid', 'en');

        $list = new OrganizationUrlList(array($urlOne, $urlTwo));

        $unknownElementSeen = false;
        $urlOneSeen = $urlTwoSeen = false;

        foreach ($list as $url) {
            if (!$urlOneSeen && $url === $urlOne) {
                $urlOneSeen = true;
            } elseif (!$urlTwoSeen && $url === $urlTwo) {
                $urlTwoSeen = true;
            } else {
                $unknownElementSeen = true;
            }
        }

        $this->assertFalse($unknownElementSeen, 'Found unknown element while iterating over OrganizationUrlList');
        $this->assertTrue($urlOneSeen, 'Missing expected element nameOne when iterating over OrganizationUrlList');
        $this->assertTrue($urlTwoSeen, 'Missing expected element nameTwo when iterating over OrganizationUrlList');
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function the_elements_in_an_organization_url_list_can_be_counted()
    {
        $numberOne   = new OrganizationUrl('https://www.openconext.org', 'en');
        $numberTwo   = new OrganizationUrl('https://www.domain.invalid', 'en');
        $numberThree = new OrganizationUrl('https://www.google.com', 'en');

        $twoElements   = new OrganizationUrlList(array($numberThree, $numberTwo));
        $threeElements = new OrganizationUrlList(array($numberThree, $numberTwo, $numberOne));

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
        $numberOne = new OrganizationUrl('https://www.openconext.org', 'en');
        $numberTwo = new OrganizationUrl('https://www.domain.invalid', 'en');

        $list = new OrganizationUrlList(array($numberTwo, $numberOne, $numberTwo));

        $this->assertSame(array($numberTwo, $numberOne, $numberTwo), $list->toArray());
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function deserializing_a_serialized_organization_url_list_results_in_an_equal_value_object()
    {
        $urlOne = new OrganizationUrl('https://www.openconext.org', 'en');
        $urlTwo = new OrganizationUrl('https://www.domain.invalid', 'en');

        $original     = new OrganizationUrlList(array($urlOne, $urlTwo));
        $deserialized = OrganizationUrlList::deserialize($original->serialize());

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
        OrganizationUrlList::deserialize($notArray);
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function an_organization_url_list_can_be_cast_to_string()
    {
        $urlOne = new OrganizationUrl('https://www.openconext.org', 'en');
        $urlTwo = new OrganizationUrl('https://www.domain.invalid', 'en');

        $list = new OrganizationUrlList(array($urlOne, $urlTwo));

        $string = sprintf('OrganizationUrlList[%s, %s]', (string) $urlOne, (string) $urlTwo);
        $this->assertSame($string, (string) $list);
    }
}
