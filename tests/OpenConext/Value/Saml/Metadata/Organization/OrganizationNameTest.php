<?php

namespace OpenConext\Value\Saml\Metadata\Organization;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;

class OrganizationNameTest extends UnitTest
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
    public function organization_name_must_be_a_non_empty_string($invalidArgument)
    {
        new OrganizationName($invalidArgument, 'en');
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
        new OrganizationName('OpenConext', $invalidArgument);
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function equality_is_compared_on_name_and_language()
    {
        $base                = new OrganizationName('OpenConext', 'en');
        $theSame             = new OrganizationName('OpenConext', 'en');
        $differentName       = new OrganizationName('Different', 'en');
        $differentLanguage   = new OrganizationName('OpenConext', 'nl');
        $completelyDifferent = new OrganizationName('Different', 'nl');

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse(
            $base->equals($differentName),
            'OrganizationNames with different names must not be equal'
        );
        $this->assertFalse(
            $base->equals($differentLanguage),
            'OrganizationNames with different languages must not be equal'
        );
        $this->assertFalse(
            $base->equals($completelyDifferent),
            'OrganizationNames with different language and different name must not be equal'
        );
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function the_name_can_be_retrieved()
    {
        $name = 'OpenConext';

        $organizationName = new OrganizationName($name, 'en');

        $this->assertEquals($name, $organizationName->getName());
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function the_language_can_be_retrieved()
    {
        $language = 'en';

        $organizationName = new OrganizationName('OpenConext', $language);

        $this->assertEquals($language, $organizationName->getLanguage());
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function deserializing_a_serialized_organization_name_results_in_an_equal_value_object()
    {
        $original     = new OrganizationName('OpenConext', 'en_US');
        $deserialized = OrganizationName::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     * @test
     * @group metadata
     * @group organization
     *
     * @dataProvider invalidDeserializationDataProvider
     * @expectedException InvalidArgumentException
     *
     * @param mixed $invalidData
     */
    public function deserialization_requires_valid_data($invalidData)
    {
        OrganizationName::deserialize($invalidData);
    }

    /**
     * @return array
     */
    public function invalidDeserializationDataProvider()
    {
        return array(
            'data is not an array' => array('foobar'),
            'missing both keys'    => array(array('a')),
            'missing name key'     => array('a' => 'foobar', 'language' => 'en_US'),
            'missing language key' => array('name' => 'OpenConext')
        );
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function an_organization_name_can_be_cast_to_string()
    {
        $name     = 'OpenConext';
        $language = 'en';

        $organizationName = new OrganizationName($name, $language);

        $this->assertEquals(sprintf('[%s] %s', $language, $name), (string) $organizationName);
    }
}
