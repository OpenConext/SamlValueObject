<?php

namespace OpenConext\Value\Saml\Metadata;

use PHPUnit_Framework_TestCase as UnitTest;

class ShibbolethMetadataScopeListTest extends UnitTest
{
    /**
     * @test
     * @group metadata
     */
    public function adding_scope_to_a_list_creates_a_new_list()
    {
        $list = new ShibbolethMetadataScopeList(array(ShibbolethMetadataScope::literal('first')));

        $otherList = $list->add(ShibbolethMetadataScope::literal('second'));

        $this->assertNotSame($list, $otherList);
    }

    /**
     * @test
     * @group metadata
     */
    public function a_string_is_in_scope_if_it_matches_at_least_one_scope_in_the_list()
    {
        $list = new ShibbolethMetadataScopeList(array(
            ShibbolethMetadataScope::literal('first'),
            ShibbolethMetadataScope::regexp('/a{3,4}/i')
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
     * @expectedException \OpenConext\Value\Exception\InvalidArgumentException
     *
     * @param mixed $invalidScope
     */
    public function a_list_can_only_determine_if_strings_are_in_scope($invalidScope)
    {
        static $list = null;

        if (!$list) {
            $list = new ShibbolethMetadataScopeList(array(
                ShibbolethMetadataScope::literal('first'),
                ShibbolethMetadataScope::regexp('/a{3,4}/i')
            ));
        }

        $list->inScope($invalidScope);
    }
}
