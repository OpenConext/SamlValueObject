<?php

namespace OpenConext\Value\Saml\Metadata\Common;

use OpenConext\Value\Exception\InvalidArgumentException;


class LocalizedUriTest extends \PHPUnit\Framework\TestCase
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
    public function uri_must_be_a_non_empty_string($notStringOrEmtpyString)
    {
        $this->expectException(\InvalidArgumentException::class);
        new LocalizedUri($notStringOrEmtpyString, 'en');
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
        new LocalizedUri('some:uri', $notStringOrEmtpyString);
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function uri_can_be_retrieved()
    {
        $uri = 'some:uri';

        $localizedUri = new LocalizedUri($uri, 'en');

        $this->assertEquals($uri, $localizedUri->getUri());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function language_can_be_retrieved()
    {
        $language = 'en_US';

        $localizedUri = new LocalizedUri('some:uri', $language);

        $this->assertEquals($language, $localizedUri->getLanguage());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function equality_is_verified_on_uri_and_language()
    {
        $base              = new LocalizedUri('some:uri', 'en');
        $same              = new LocalizedUri('some:uri', 'en');
        $differentUri      = new LocalizedUri('some:other:uri', 'en');
        $differentLanguage = new LocalizedUri('some:uri', 'en_US');

        $this->assertTrue($base->equals($same));
        $this->assertFalse($base->equals($differentUri));
        $this->assertFalse($base->equals($differentLanguage));
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserializing_a_serialized_localized_uri_yields_an_equal_value_object()
    {
        $original = new LocalizedUri('some:uri', 'en');

        $deserialized = LocalizedUri::deserialize($original->serialize());

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
        LocalizedUri::deserialize($notArray);
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
        LocalizedUri::deserialize($invalidData);
    }

    public static function invalidDataProvider()
    {
        return array(
            'no matching keys' => array(array('foo' => 'some:uri', 'bar' => 'en_US')),
            'no uri'           => array(array('language' => 'en_US')),
            'no language'      => array(array('uri' => 'some:uri')),
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('common')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function a_localized_uri_can_be_cast_to_string()
    {
        $this->assertIsString((string) new LocalizedUri('some:uri', 'en'));
    }
}
