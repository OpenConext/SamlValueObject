<?php

namespace OpenConext\Value\Saml\Metadata;

use OpenConext\Value\Exception\InvalidArgumentException;
use OpenConext\Value\Saml\Metadata\Organization\OrganizationDisplayName;
use OpenConext\Value\Saml\Metadata\Organization\OrganizationDisplayNameList;
use OpenConext\Value\Saml\Metadata\Organization\OrganizationName;
use OpenConext\Value\Saml\Metadata\Organization\OrganizationNameList;
use OpenConext\Value\Saml\Metadata\Organization\OrganizationUrl;
use OpenConext\Value\Saml\Metadata\Organization\OrganizationUrlList;
use PHPUnit_Framework_TestCase as UnitTest;

class OrganizationTest extends UnitTest
{
    /**
     * @test
     * @group metadata
     * @group organization
     *
     * @expectedException InvalidArgumentException
     */
    public function organization_requires_at_least_one_organization_name()
    {
        new Organization(
            new OrganizationNameList(array()),
            new OrganizationDisplayNameList(array(new OrganizationDisplayName('OpenConext', 'en'))),
            new OrganizationUrlList(array(new OrganizationUrl('https://www.opencontext.org', 'en')))
        );
    }

    /**
     * @test
     * @group metadata
     * @group organization
     *
     * @expectedException InvalidArgumentException
     */
    public function organization_requires_at_least_one_organization_display_name()
    {
        new Organization(
            new OrganizationNameList(array(new OrganizationName('OpenConext', 'en'))),
            new OrganizationDisplayNameList(array()),
            new OrganizationUrlList(array(new OrganizationUrl('https://www.opencontext.org', 'en')))
        );
    }

    /**
     * @test
     * @group metadata
     * @group organization
     *
     * @expectedException InvalidArgumentException
     */
    public function organization_requires_at_least_one_organization_url()
    {
        new Organization(
            new OrganizationNameList(array(new OrganizationName('OpenConext', 'en'))),
            new OrganizationDisplayNameList(array(new OrganizationDisplayName('OpenConext', 'en'))),
            new OrganizationUrlList(array())
        );
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function equality_is_verified_on_all_properties()
    {
        $names = new OrganizationNameList(array(new OrganizationName('OpenConext', 'en')));
        $displayNames = new OrganizationDisplayNameList(array(new OrganizationDisplayName('OpenConext', 'en')));
        $urls = new OrganizationUrlList(array(new OrganizationUrl('https://www.opencontext.org', 'en')));

        $base = new Organization($names, $displayNames, $urls);
        $same = new Organization($names, $displayNames, $urls);
        $differentNames = new Organization(
            new OrganizationNameList(array(new OrganizationName('Different', 'en'))),
            $displayNames,
            $urls
        );
        $differentDisplayNames = new Organization(
            $names,
            new OrganizationDisplayNameList(array(new OrganizationDisplayName('Different', 'en'))),
            $urls
        );
        $differentUrls = new Organization(
            $names,
            $displayNames,
            new OrganizationUrlList(array(new OrganizationUrl('https://www.google.com', 'en')))
        );
        $allDifferent = new Organization(
            new OrganizationNameList(array(new OrganizationName('OpenConext', 'en'))),
            new OrganizationDisplayNameList(array(new OrganizationDisplayName('OpenConext', 'en'))),
            new OrganizationUrlList(array(new OrganizationUrl('https://www.google.com', 'en')))
        );

        $this->assertTrue($base->equals($same));
        $this->assertFalse($base->equals($differentNames));
        $this->assertFalse($base->equals($differentDisplayNames));
        $this->assertFalse($base->equals($differentUrls));
        $this->assertFalse($base->equals($allDifferent));
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function organization_names_can_be_retrieved()
    {
        $organizationNames = new OrganizationNameList(array(new OrganizationName('OpenConext', 'en')));
        $organization = new Organization(
            $organizationNames,
            new OrganizationDisplayNameList(array(new OrganizationDisplayName('OpenConext', 'en'))),
            new OrganizationUrlList(array(new OrganizationUrl('https://www.opencontext.org', 'en')))
        );

        $this->assertSame($organizationNames, $organization->getOrganizationNames());
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function organization_display_names_can_be_retrieved()
    {
        $organizationDisplayNames = new OrganizationDisplayNameList(
            array(new OrganizationDisplayName('OpenConext', 'en'))
        );
        $organization = new Organization(
            new OrganizationNameList(array(new OrganizationName('OpenConext', 'en'))),
            $organizationDisplayNames,
            new OrganizationUrlList(array(new OrganizationUrl('https://www.opencontext.org', 'en')))
        );

        $this->assertSame($organizationDisplayNames, $organization->getOrganizationDisplayNames());
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function organization_urls_can_be_retrieved()
    {
        $organizationUrls = new OrganizationUrlList(array(new OrganizationUrl('https://www.opencontext.org', 'en')));
        $organization = new Organization(
            new OrganizationNameList(array(new OrganizationName('OpenConext', 'en'))),
            new OrganizationDisplayNameList(array(new OrganizationDisplayName('OpenConext', 'en'))),
            $organizationUrls
        );

        $this->assertSame($organizationUrls, $organization->getOrganizationUrls());
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function deserializing_a_serialized_organisation_results_in_an_equal_value_object()
    {
        $original = new Organization(
            new OrganizationNameList(array(new OrganizationName('OpenConext', 'en'))),
            new OrganizationDisplayNameList(array(new OrganizationDisplayName('OpenConext', 'en'))),
            new OrganizationUrlList(array(new OrganizationUrl('https://www.opencontext.org', 'en')))
        );
        $deserialized = Organization::deserialize($original->serialize());

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
    public function deserialization_requires_data_to_be_an_array($notArray)
    {
        Organization::deserialize($notArray);
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
    public function deserialization_requires_the_presence_of_requried_keys($invalidData)
    {
        Organization::deserialize($invalidData);
    }

    public function invalidDeserializationDataProvider()
    {
        return array(
            'missing names key' => array(array_flip(array('a', 'organization_display_names', 'organization_urls'))),
            'missing display key' => array(array_flip(array('organization_names', 'organization_urls'))),
            'missing urls key' => array(array_flip(array('organization_names', 'organization_display_names')))
        );
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function an_organization_can_be_cast_to_string()
    {
        $organization = new Organization(
            new OrganizationNameList(array(new OrganizationName('OpenConext', 'en'))),
            new OrganizationDisplayNameList(array(new OrganizationDisplayName('OpenConext', 'en'))),
            new OrganizationUrlList(array(new OrganizationUrl('https://www.opencontext.org', 'en')))
        );

        $this->assertTrue(is_string((string) $organization));
    }
}
