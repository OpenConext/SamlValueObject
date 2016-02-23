<?php

namespace OpenConext\Value\Saml\Metadata\Organization;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;

class OrganizationDisplayNameTest extends UnitTest
{
    /**
     * @test
     * @group metadata
     * @group organization
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString()
     * @expectedException InvalidArgumentException
     *
     * @param mixed $invalidArgument
     */
    public function display_name_must_be_a_non_empty_string($invalidArgument)
    {
        new OrganizationDisplayName($invalidArgument, 'en');
    }

    /**
     * @test
     * @group metadata
     * @group organization
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString()
     * @expectedException InvalidArgumentException
     *
     * @param mixed $invalidArgument
     */
    public function language_must_be_a_non_emtpy_string($invalidArgument)
    {
        new OrganizationDisplayName('OpenConext', $invalidArgument);
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function equality_is_compared_on_display_name_and_language()
    {
        $base = new OrganizationDisplayName('OpenConext', 'en');
        $theSame = new OrganizationDisplayName('OpenConext', 'en');
        $differentName = new OrganizationDisplayName('Different', 'en');
        $differentLanguage = new OrganizationDisplayName('OpenConext', 'nl');
        $completelyDifferent = new OrganizationDisplayName('Different', 'nl');

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse(
            $base->equals($differentName),
            'OrganizationDisplayNames with different display names must not be equal'
        );
        $this->assertFalse(
            $base->equals($differentLanguage),
            'OrganizationDisplayNames with different languages must not be equal'
        );
        $this->assertFalse(
            $base->equals($completelyDifferent),
            'OrganizationDisplayNames with different language and different display name must not be equal'
        );
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function the_display_name_can_be_retrieved()
    {
        $displayName = 'OpenConext';

        $organizationDisplayName = new OrganizationDisplayName($displayName, 'en');

        $this->assertEquals($displayName, $organizationDisplayName->getDisplayName());
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function the_language_can_be_retrieved()
    {
        $language = 'en';

        $organizationDisplayName = new OrganizationDisplayName('OpenConext', $language);

        $this->assertEquals($language, $organizationDisplayName->getLanguage());
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function deserializing_a_serialized_organization_display_name_results_in_an_equal_value_object()
    {
        $displayName = 'OpenConext.org';
        $language    = 'en_US';

        $original     = new OrganizationDisplayName($displayName, $language);
        $deserialized = OrganizationDisplayName::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     * @test
     * @group        metadata
     * @group        organization
     *
     * @dataProvider invalidDeserializationDataProvider
     * @expectedException InvalidArgumentException
     *
     * @param mixed $invalidData
     */
    public function deserialization_requires_valid_data($invalidData)
    {
        OrganizationDisplayName::deserialize($invalidData);
    }

    /**
     * @return array
     */
    public function invalidDeserializationDataProvider()
    {
        return array(
            'data is not an array'     => array('foobar'),
            'missing both keys'        => array(array('a')),
            'missing display_name key' => array('a' => 'foobar', 'language' => 'en_US'),
            'missing language key'     => array('display_name' => 'OpenConext.org')
        );
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function an_organization_display_name_can_be_cast_to_string()
    {
        $displayName = 'OpenConext';
        $language    = 'en';

        $organizationDisplayName = new OrganizationDisplayName($displayName, $language);

        $this->assertEquals(sprintf('[%s] %s', $language, $displayName), (string) $organizationDisplayName);
    }
}
