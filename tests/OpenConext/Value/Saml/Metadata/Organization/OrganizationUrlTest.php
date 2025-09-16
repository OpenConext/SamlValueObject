<?php

namespace OpenConext\Value\Saml\Metadata\Organization;

use OpenConext\Value\Exception\InvalidArgumentException;


class OrganizationUrlTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     *
     * @param mixed $invalidArgument
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notStringOrEmptyString')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function organization_url_must_be_a_non_empty_string($invalidArgument)
    {
        $this->expectException(InvalidArgumentException::class);
        new OrganizationUrl($invalidArgument, 'en');
    }

    /**
     *
     *
     * @param mixed $invalidArgument
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notStringOrEmptyString')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function language_must_be_a_non_emtpy_string($invalidArgument)
    {
        $this->expectException(\InvalidArgumentException::class);
        new OrganizationUrl('https://www.openconext.org', $invalidArgument);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
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

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_url_can_be_retrieved()
    {
        $url = 'https://www.openconext.org';

        $organizationUrl = new OrganizationUrl($url, 'en');

        $this->assertEquals($url, $organizationUrl->getUrl());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_language_can_be_retrieved()
    {
        $language = 'en';

        $organizationUrl = new OrganizationUrl('https://www.openconext.org', $language);

        $this->assertEquals($language, $organizationUrl->getLanguage());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserializing_a_serialized_organization_url_results_in_an_equal_value_object()
    {
        $original     = new OrganizationUrl('https://www.openconext.org', 'en_US');
        $deserialized = OrganizationUrl::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     *
     *
     * @param mixed $invalidData
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('invalidDeserializationDataProvider')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserialization_requires_valid_data($invalidData)
    {
        $this->expectException(InvalidArgumentException::class);
        OrganizationUrl::deserialize($invalidData);
    }

    /**
     * @return array
     */
    public static function invalidDeserializationDataProvider()
    {
        return array(
            'data is not an array' => array('foobar'),
            'missing both keys'    => array(array('a')),
            'missing url key'      => array(array('a' => 'https://www.openconext.org', 'language' => 'en_US')),
            'missing language key' => array(array('url' => 'https://www.openconext.org'))
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function an_organization_url_can_be_cast_to_string()
    {
        $url      = 'https://www.openconext.org';
        $language = 'en';

        $organizationUrl = new OrganizationUrl($url, $language);

        $this->assertEquals(sprintf('[%s] %s', $language, $url), (string)$organizationUrl);
    }
}
