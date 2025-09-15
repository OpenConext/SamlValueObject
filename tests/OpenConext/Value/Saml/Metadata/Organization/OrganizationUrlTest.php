<?php

namespace OpenConext\Value\Saml\Metadata\Organization;

use OpenConext\Value\Exception\InvalidArgumentException;


class OrganizationUrlTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @group        metadata
     * @group        organization
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString()
     *
     * @param mixed $invalidArgument
     */
    public function organization_url_must_be_a_non_empty_string($invalidArgument)
    {
        $this->expectException(InvalidArgumentException::class);
        new OrganizationUrl($invalidArgument, 'en');
    }

    /**
     * @test
     * @group        metadata
     * @group        organization
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString()
     *
     * @param mixed $invalidArgument
     */
    public function language_must_be_a_non_emtpy_string($invalidArgument)
    {
        $this->expectException(\InvalidArgumentException::class);
        new OrganizationUrl('https://www.openconext.org', $invalidArgument);
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function equality_is_compared_on_url_and_language()
    {
        $base                = new OrganizationUrl('https://www.openconext.org', 'en');
        $theSame             = new OrganizationUrl('https://www.openconext.org', 'en');
        $differentUrl        = new OrganizationUrl('https://www.domain.invalid', 'en');
        $differentLanguage   = new OrganizationUrl('https://www.openconext.org', 'nl');
        $completelyDifferent = new OrganizationUrl('https://www.domain.invalid', 'nl');

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse(
            $base->equals($differentUrl),
            'OrganizationUrls with different urls must not be equal'
        );
        $this->assertFalse(
            $base->equals($differentLanguage),
            'OrganizationUrls with different languages must not be equal'
        );
        $this->assertFalse(
            $base->equals($completelyDifferent),
            'OrganizationUrls with different language and different url must not be equal'
        );
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function the_url_can_be_retrieved()
    {
        $url = 'https://www.openconext.org';

        $organizationUrl = new OrganizationUrl($url, 'en');

        $this->assertEquals($url, $organizationUrl->getUrl());
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function the_language_can_be_retrieved()
    {
        $language = 'en';

        $organizationUrl = new OrganizationUrl('https://www.openconext.org', $language);

        $this->assertEquals($language, $organizationUrl->getLanguage());
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function deserializing_a_serialized_organization_url_results_in_an_equal_value_object()
    {
        $original     = new OrganizationUrl('https://www.openconext.org', 'en_US');
        $deserialized = OrganizationUrl::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     * @test
     * @group        metadata
     * @group        organization
     *
     * @dataProvider invalidDeserializationDataProvider
     *
     * @param mixed $invalidData
     */
    public function deserialization_requires_valid_data($invalidData)
    {
        $this->expectException(InvalidArgumentException::class);
        try {
            OrganizationUrl::deserialize($invalidData);
        }catch (\Throwable $e){
            var_dump(get_debug_type($e));
        }
    }

    /**
     * @return array
     */
    public static function invalidDeserializationDataProvider()
    {
        return array(
            'data is not an array' => array('foobar'),
            'missing both keys'    => array(array('a')),
            'missing url key'      => array('a' => 'https://www.openconext.org', 'language' => 'en_US'),
            'missing language key' => array('url' => 'https://www.openconext.org')
        );
    }

    /**
     * @test
     * @group metadata
     * @group organization
     */
    public function an_organization_url_can_be_cast_to_string()
    {
        $url      = 'https://www.openconext.org';
        $language = 'en';

        $organizationUrl = new OrganizationUrl($url, $language);

        $this->assertEquals(sprintf('[%s] %s', $language, $url), (string)$organizationUrl);
    }
}
