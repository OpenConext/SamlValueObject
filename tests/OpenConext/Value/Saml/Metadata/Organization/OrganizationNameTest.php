<?php

namespace OpenConext\Value\Saml\Metadata\Organization;

use OpenConext\Value\Exception\InvalidArgumentException;


class OrganizationNameTest extends \PHPUnit\Framework\TestCase
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
    public function organization_name_must_be_a_non_empty_string($invalidArgument)
    {
        $this->expectException(\InvalidArgumentException::class);
        new OrganizationName($invalidArgument, 'en');
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
        new OrganizationName('OpenConext', $invalidArgument);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
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

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_name_can_be_retrieved()
    {
        $name = 'OpenConext';

        $organizationName = new OrganizationName($name, 'en');

        $this->assertEquals($name, $organizationName->getName());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_language_can_be_retrieved()
    {
        $language = 'en';

        $organizationName = new OrganizationName('OpenConext', $language);

        $this->assertEquals($language, $organizationName->getLanguage());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserializing_a_serialized_organization_name_results_in_an_equal_value_object()
    {
        $original     = new OrganizationName('OpenConext', 'en_US');
        $deserialized = OrganizationName::deserialize($original->serialize());

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
        $this->expectException(\InvalidArgumentException::class);
        OrganizationName::deserialize($invalidData);
    }

    /**
     * @return array
     */
    public static function invalidDeserializationDataProvider()
    {
        return array(
            'data is not an array' => array('foobar'),
            'missing both keys'    => array(array('a')),
            'missing name key'     => array(array('a' => 'foobar', 'language' => 'en_US')),
            'missing language key' => array(array('name' => 'OpenConext'))
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('organization')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function an_organization_name_can_be_cast_to_string()
    {
        $name     = 'OpenConext';
        $language = 'en';

        $organizationName = new OrganizationName($name, $language);

        $this->assertEquals(sprintf('[%s] %s', $language, $name), (string) $organizationName);
    }
}
