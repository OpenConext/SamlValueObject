<?php

namespace OpenConext\Value\Saml\Metadata;

use OpenConext\Value\Exception\InvalidArgumentException;

use stdClass;

class ShibbolethMetadataScopeListTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @group metadata
     */
    public function all_elements_must_be_a_shibboleth_metadata_scope()
    {
        $this->expectException(\InvalidArgumentException::class);
        $elements = array(
            ShibbolethMetadataScope::literal('foobar'),
            ShibbolethMetadataScope::regexp('abc'),
            new stdClass()
        );

        new ShibbolethMetadataScopeList($elements);
    }

    /**
     * @test
     * @group metadata
     */
    public function a_string_is_in_scope_if_it_matches_at_least_one_scope_in_the_list()
    {
        $list = new ShibbolethMetadataScopeList(array(
            ShibbolethMetadataScope::literal('first'),
            ShibbolethMetadataScope::regexp('a{3,4}')
        ));

        $this->assertTrue($list->inScope('first'));
        $this->assertTrue($list->inScope('aAa'));
        $this->assertFalse($list->inScope('nope'));
    }

    /**
     * @test
     * @group metadata
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notString
     *
     * @param mixed $invalidScope
     */
    public function a_list_can_only_determine_if_strings_are_in_scope($invalidScope)
    {
        $this->expectException(\OpenConext\Value\Exception\InvalidArgumentException::class);
        static $list = null;

        if (!$list) {
            $list = new ShibbolethMetadataScopeList(array(
                ShibbolethMetadataScope::literal('first'),
                ShibbolethMetadataScope::regexp('a{3,4}')
            ));
        }

        $list->inScope($invalidScope);
    }

    /**
     * @test
     * @group metadata
     */
    public function adding_scope_to_a_list_creates_a_new_list()
    {
        $firstScope = ShibbolethMetadataScope::literal('first');
        $secondScope = ShibbolethMetadataScope::regexp('abc');

        $list = new ShibbolethMetadataScopeList(array($firstScope));
        $newList = $list->add($secondScope);

        $this->assertNotSame($list, $newList, 'Adding an element to a list should return a new list');

        $this->assertTrue($list->contains($firstScope));
        $this->assertFalse($list->contains($secondScope));

        $this->assertTrue($newList->contains($firstScope));
        $this->assertTrue($newList->contains($secondScope));
    }

    /**
     * @test
     * @group metadata
     */
    public function presence_of_a_scope_can_be_tested()
    {
        $firstScope  = ShibbolethMetadataScope::literal('in scope');
        $secondScope = ShibbolethMetadataScope::regexp('abc');
        $notInList   = ShibbolethMetadataScope::literal('not in list');

        $list = new ShibbolethMetadataScopeList(array($firstScope, $secondScope));

        $this->assertTrue($list->contains($firstScope));
        $this->assertTrue($list->contains($secondScope));
        $this->assertFalse($list->contains($notInList));
    }

    /**
     * @test
     * @group metadata
     */
    public function the_index_of_a_scope_can_be_retrieved()
    {
        $firstScope  = ShibbolethMetadataScope::literal('in scope');
        $secondScope = ShibbolethMetadataScope::regexp('abc');
        $notInList   = ShibbolethMetadataScope::literal('not in list');

        $list = new ShibbolethMetadataScopeList(array($firstScope, $secondScope));

        $this->assertEquals(0, $list->indexOf($firstScope));
        $this->assertEquals(1, $list->indexOf($secondScope));
        $this->assertEquals(-1, $list->indexOf($notInList), 'An element not in the list has an index of -1');
    }

    /**
     * @test
     * @group metadata
     */
    public function a_scope_can_be_retrieved_by_index()
    {
        $firstScope  = ShibbolethMetadataScope::literal('in scope');
        $secondScope = ShibbolethMetadataScope::regexp('abc');

        $list = new ShibbolethMetadataScopeList(array($firstScope, $secondScope));

        $this->assertSame($firstScope, $list->get(0));
        $this->assertSame($secondScope, $list->get(1));
    }

    /**
     * @test
     * @group metadata
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notInteger
     */
    public function index_to_retrieve_the_element_of_must_be_an_integer($invalidArgument)
    {
        $this->expectException(\InvalidArgumentException::class);
        $firstScope  = ShibbolethMetadataScope::literal('in scope');
        $secondScope = ShibbolethMetadataScope::regexp('abc');

        $list = new ShibbolethMetadataScopeList(array($firstScope, $secondScope));

        $list->get($invalidArgument);
    }

    /**
     * @test
     * @group metadata
     */
    public function an_exception_is_thrown_when_attempting_to_get_an_element_with_a_negative_index()
    {
        $this->expectException(\OpenConext\Value\Exception\IndexOutOfBoundsException::class);
        $firstScope  = ShibbolethMetadataScope::literal('in scope');
        $secondScope = ShibbolethMetadataScope::regexp('abc');

        $list = new ShibbolethMetadataScopeList(array($firstScope, $secondScope));

        $list->get(-1);
    }

    /**
     * @test
     * @group metadata
     */
    public function an_exception_is_thrown_when_attempting_to_get_an_element_with_an_index_larger_than_the_list_size()
    {
        $this->expectException(\OpenConext\Value\Exception\IndexOutOfBoundsException::class);
        $firstScope  = ShibbolethMetadataScope::literal('in scope');
        $secondScope = ShibbolethMetadataScope::regexp('abc');

        $list = new ShibbolethMetadataScopeList(array($firstScope, $secondScope));

        $list->get(2);
    }

    /**
     * @test
     * @group metadata
     */
    public function a_scope_can_be_searched_for()
    {
        $predicate = function (ShibbolethMetadataScope $scope) {
            return $scope->equals(ShibbolethMetadataScope::regexp('abc'));
        };

        $firstScope  = ShibbolethMetadataScope::literal('in scope');
        $secondScope = ShibbolethMetadataScope::regexp('abc');

        $list = new ShibbolethMetadataScopeList(array($firstScope, $secondScope));

        $this->assertSame($secondScope, $list->find($predicate));
    }

    /**
     * @test
     * @group metadata
     */
    public function find_returns_the_first_matching_element()
    {
        $predicate = function (ShibbolethMetadataScope $scope) {
            return $scope->equals(ShibbolethMetadataScope::regexp('abc'));
        };

        $firstScope  = ShibbolethMetadataScope::literal('in scope');
        $secondScope = ShibbolethMetadataScope::regexp('abc');
        $notReturned = ShibbolethMetadataScope::regexp('abc');

        $list = new ShibbolethMetadataScopeList(array($firstScope, $secondScope, $notReturned));

        $this->assertSame($secondScope, $list->find($predicate));
    }

    /**
     * @test
     * @group metadata
     */
    public function null_is_returned_when_no_match_is_found()
    {
        $predicate = function () {
            return false;
        };

        $firstScope  = ShibbolethMetadataScope::literal('in scope');
        $secondScope = ShibbolethMetadataScope::regexp('abc');

        $list = new ShibbolethMetadataScopeList(array($firstScope, $secondScope));

        $this->assertNull($list->find($predicate));
    }

    /**
     * @test
     * @group metadata
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notCallable
     *
     * @param mixed $notCallable
     */
    public function find_predicate_must_be_a_callable($notCallable)
    {
        $this->expectException(\InvalidArgumentException::class);
        $firstScope  = ShibbolethMetadataScope::literal('in scope');
        $secondScope = ShibbolethMetadataScope::regexp('abc');

        $list = new ShibbolethMetadataScopeList(array($firstScope, $secondScope));

        $list->find($notCallable);
    }

    /**
     * @test
     * @group metadata
     */
    public function lists_are_only_equal_when_containing_the_same_elements_in_the_same_order()
    {
        $firstScope  = ShibbolethMetadataScope::literal('in scope');
        $secondScope = ShibbolethMetadataScope::regexp('abc');
        $thirdsScope = ShibbolethMetadataScope::literal('also in scope');

        $base           = new ShibbolethMetadataScopeList(array($firstScope, $secondScope));
        $theSame        = new ShibbolethMetadataScopeList(array($firstScope, $secondScope));
        $differentOrder = new ShibbolethMetadataScopeList(array($secondScope, $firstScope));
        $lessElements   = new ShibbolethMetadataScopeList(array($firstScope));
        $moreElements   = new ShibbolethMetadataScopeList(array($firstScope, $secondScope, $thirdsScope));

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($differentOrder));
        $this->assertFalse($base->equals($lessElements));
        $this->assertFalse($base->equals($moreElements));
    }

    /**
     * @test
     * @group metadata
     */
    public function a_list_can_be_iterated_over()
    {
        $literal = ShibbolethMetadataScope::literal('foo');
        $regexp = ShibbolethMetadataScope::regexp('a{3,4}');

        $list = new ShibbolethMetadataScopeList(array($literal, $regexp));

        $unknownScopeSeen = false;
        $literalSeen = $regexpSeen = false;

        foreach ($list as $scope) {
            if (!$literalSeen && $scope === $literal) {
                $literalSeen = true;
            } elseif (!$regexpSeen && $scope === $regexp) {
                $regexpSeen = true;
            } else {
                $unknownScopeSeen = true;
            }
        }

        $this->assertFalse($unknownScopeSeen, 'Unknown scope seen when iterating over ScopeList with known elements');
        $this->assertTrue($literalSeen, 'Missing literal scope when iterating over ScopeList with literal scope');
        $this->assertTrue($regexpSeen, 'Missing regexp scope when iterating over ScopeList with regexp scope');
    }

    /**
     * @test
     * @group metadata
     */
    public function a_list_can_be_counted()
    {
        $literal = ShibbolethMetadataScope::literal('foo');
        $regexp  = ShibbolethMetadataScope::regexp('a{3,4}');

        $list = new ShibbolethMetadataScopeList(array($literal, $regexp));

        $this->assertEquals(2, count($list));
    }

    /**
     * @test
     * @group metadata
     */
    public function a_list_exposes_an_array_containing_its_elements()
    {
        $literal = ShibbolethMetadataScope::literal('foo');
        $regexp  = ShibbolethMetadataScope::regexp('a{3,4}');

        $list = new ShibbolethMetadataScopeList(array($literal, $regexp));

        $this->assertSame(
            array($literal, $regexp),
            $list->toArray()
        );
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function deserializing_a_serialized_shibboleth_metadata_scope_list_results_in_an_equal_value_object()
    {
        $literal = ShibbolethMetadataScope::literal('foo');
        $regexp  = ShibbolethMetadataScope::regexp('a{3,4}');

        $original = new ShibbolethMetadataScopeList(array($literal, $regexp));
        $deserialized = ShibbolethMetadataScopeList::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     * @test
     * @group        metadata
     * @group        contactperson
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notArray
     *
     * @param mixed $notArray
     */
    public function deserialization_requires_an_array($notArray)
    {
        $this->expectException(\InvalidArgumentException::class);
        ShibbolethMetadataScopeList::deserialize($notArray);
    }

    /**
     * @test
     * @group metadata
     */
    public function a_list_can_be_cast_to_string()
    {
        $literal = ShibbolethMetadataScope::literal('foo');
        $regexp  = ShibbolethMetadataScope::regexp('a{3,4}');

        $list = new ShibbolethMetadataScopeList(array($literal, $regexp));

        $this->assertStringStartsWith('ShibbolethMetadataScopeList', (string) $list);
    }
}
