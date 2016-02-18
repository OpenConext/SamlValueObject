<?php

namespace OpenConext\Value\Saml\Metadata;

use PHPUnit_Framework_TestCase as UnitTest;

class ShibbolethMetadataScopeTest extends UnitTest
{
    /**
     * @test
     * @group metadata
     *
     * @expectedException \OpenConext\Value\Exception\InvalidArgumentException
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     *
     * @param mixed $literal
     */
    public function a_literal_scope_only_accepts_a_non_empty_string($literal)
    {
        ShibbolethMetadataScope::literal($literal);
    }

    /**
     * @test
     * @group metadata
     *
     * @expectedException \OpenConext\Value\Exception\InvalidArgumentException
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     *
     * @param mixed $regexp
     */
    public function a_regex_scope_only_accepts_a_non_empty_string($regexp)
    {
       ShibbolethMetadataScope::literal($regexp);
    }

    /**
     * @test
     * @group metadata
     */
    public function a_literal_scope_is_not_equal_to_a_regexp_scope()
    {
        $literalScope = ShibbolethMetadataScope::literal('/notEqual/');
        $regexpScope  = ShibbolethMetadataScope::regexp('/notEqual/');

        $this->assertFalse($literalScope->equals($regexpScope));
    }

    /**
     * @test
     * @group metadata
     */
    public function literal_scopes_are_compared_based_on_the_literal()
    {
        $base      = ShibbolethMetadataScope::literal('literal');
        $theSame   = ShibbolethMetadataScope::literal('literal');
        $different = ShibbolethMetadataScope::literal('different');

        $this->assertTrue(
            $base->equals($theSame),
            'Two literal scopes must be the same if they are created with the same literal'
        );

        $this->assertFalse(
            $base->equals($different),
            'Two literal scopes must be different if they are created with different literals'
        );
    }

    /**
     * @test
     * @group metadata
     */
    public function regex_scopes_are_compared_based_on_the_regexp_given()
    {
        $base      = ShibbolethMetadataScope::regexp('/[0-9a-z]{8}/');
        $theSame   = ShibbolethMetadataScope::regexp('/[0-9a-z]{8}/');
        $different = ShibbolethMetadataScope::regexp('/different/');

        $this->assertTrue(
            $base->equals($theSame),
            'Two regexp scopes must be the same if they are created with the same regexp'
        );

        $this->assertFalse(
            $base->equals($different),
            'Two literal scopes must be different if they are created with different literals'
        );
    }

    /**
     * @test
     * @group metadata
     */
    public function a_literal_scopes_allows_only_exact_matches()
    {
        $scope = ShibbolethMetadataScope::literal('abcde');

        $this->assertTrue($scope->allows('abcde'));
        $this->assertFalse($scope->allows('abcd'));
        $this->assertFalse($scope->allows('abcdef'));
    }

    /**
     * @test
     * @group metadata
     */
    public function a_regex_scope_allows_matches()
    {
        $scope = ShibbolethMetadataScope::regexp('/a{3,4}/i');

        $this->assertTrue($scope->allows('aaa'));
        $this->assertTrue($scope->allows('aAAa'));
        $this->assertFalse($scope->allows('aaba'));
        $this->assertFalse($scope->allows('1231'));
    }
}
