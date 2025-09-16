<?php

namespace OpenConext\Value\Saml\Metadata\Common;

use OpenConext\Value\Exception\InvalidArgumentException;


class LocalizedNameTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     *
     * @param mixed $notStringOrEmtpyString
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notStringOrEmptyString')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function name_must_be_a_non_empty_string($notStringOrEmtpyString)
    {
        $this->expectException(\InvalidArgumentException::class);
        new LocalizedName($notStringOrEmtpyString, 'en');
    }

    /**
     *
     *
     * @param mixed $notStringOrEmtpyString
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notStringOrEmptyString')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function language_must_be_a_non_empty_string($notStringOrEmtpyString)
    {
        $this->expectException(\InvalidArgumentException::class);
        new LocalizedName('OpenConext', $notStringOrEmtpyString);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function name_can_be_retrieved()
    {
        $name = 'OpenConext';

        $localizedName = new LocalizedName($name, 'en');

        $this->assertEquals($name, $localizedName->getName());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function language_can_be_retrieved()
    {
        $language = 'en_US';

        $localizedName = new LocalizedName('OpenConext', $language);

        $this->assertEquals($language, $localizedName->getLanguage());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function equality_is_verified_on_name_and_language()
    {
        $base              = new LocalizedName('OpenConext', 'en');
        $same              = new LocalizedName('OpenConext', 'en');
        $differentName     = new LocalizedName('OpenConext.org', 'en');
        $differentLanguage = new LocalizedName('OpenConext', 'en_US');

        $this->assertTrue($base->equals($same));
        $this->assertFalse($base->equals($differentName));
        $this->assertFalse($base->equals($differentLanguage));
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserializing_a_serialized_localized_name_yields_an_equal_value_object()
    {
        $original = new LocalizedName('OpenConext', 'en');

        $deserialized = LocalizedName::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     *
     *
     * @param mixed $notArray
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notArray')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserialization_requires_data_to_be_an_array($notArray)
    {
        $this->expectException(\InvalidArgumentException::class);
        LocalizedName::deserialize($notArray);
    }

    /**
     *
     *
     * @param array $invalidData
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('invalidDataProvider')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserialization_requires_all_required_keys_to_be_present($invalidData)
    {
        $this->expectException(\InvalidArgumentException::class);
        LocalizedName::deserialize($invalidData);
    }

    public static function invalidDataProvider()
    {
        return array(
            'no matching keys' => array(array('foo' => 'OpenConext', 'bar' => 'en_US')),
            'no name'          => array(array('language' => 'en_US')),
            'no language'      => array(array('name' => 'OpenConext')),
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function a_localized_name_can_be_cast_to_string()
    {
        $this->assertIsString((string) new LocalizedName('OpenConext', 'en'));
    }
}
