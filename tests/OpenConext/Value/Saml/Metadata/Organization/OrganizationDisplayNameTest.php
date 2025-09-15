<?php

namespace OpenConext\Value\Saml\Metadata\Organization;

use OpenConext\Value\Exception\InvalidArgumentException;

class OrganizationDisplayNameTest extends \PHPUnit\Framework\TestCase
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
    public function display_name_must_be_a_non_empty_string($invalidArgument)
    {
        $this->expectException(InvalidArgumentException::class);
        new OrganizationDisplayName($invalidArgument, 'en');
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
        $this->expectException(\OpenConext\Value\Exception\InvalidArgumentException::class);
        new OrganizationDisplayName('OpenConext', $invalidArgument);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
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

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_display_name_can_be_retrieved()
    {
        $displayName = 'OpenConext';

        $organizationDisplayName = new OrganizationDisplayName($displayName, 'en');

        $this->assertEquals($displayName, $organizationDisplayName->getDisplayName());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_language_can_be_retrieved()
    {
        $language = 'en';

        $organizationDisplayName = new OrganizationDisplayName('OpenConext', $language);

        $this->assertEquals($language, $organizationDisplayName->getLanguage());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserializing_a_serialized_organization_display_name_results_in_an_equal_value_object()
    {
        $displayName = 'OpenConext.org';
        $language    = 'en_US';

        $original     = new OrganizationDisplayName($displayName, $language);
        $deserialized = OrganizationDisplayName::deserialize($original->serialize());

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
            OrganizationDisplayName::deserialize($invalidData);
    }

    /**
     * @return array
     */
    public static function invalidDeserializationDataProvider()
    {
        return array(
            'data is not an array'     => array('foobar'),
            'missing both keys'        => array(array('a')),
            'missing display_name key' => array(array('a' => 'foobar', 'language' => 'en_US')),
            'missing language key'     => array(array('display_name' => 'OpenConext.org'))
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function an_organization_display_name_can_be_cast_to_string()
    {
        $displayName = 'OpenConext';
        $language    = 'en';

        $organizationDisplayName = new OrganizationDisplayName($displayName, $language);

        $this->assertEquals(sprintf('[%s] %s', $language, $displayName), (string) $organizationDisplayName);
    }
}
