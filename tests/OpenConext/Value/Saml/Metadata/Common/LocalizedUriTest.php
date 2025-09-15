<?php

namespace OpenConext\Value\Saml\Metadata\Common;

use OpenConext\Value\Exception\InvalidArgumentException;


class LocalizedUriTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @group metadata
     * @group common
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     *
     * @param mixed $notStringOrEmtpyString
     */
    public function uri_must_be_a_non_empty_string($notStringOrEmtpyString)
    {
        $this->expectException(\InvalidArgumentException::class);
        new LocalizedUri($notStringOrEmtpyString, 'en');
    }

    /**
     * @test
     * @group metadata
     * @group common
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     *
     * @param mixed $notStringOrEmtpyString
     */
    public function language_must_be_a_non_empty_string($notStringOrEmtpyString)
    {
        $this->expectException(\InvalidArgumentException::class);
        new LocalizedUri('some:uri', $notStringOrEmtpyString);
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function uri_can_be_retrieved()
    {
        $uri = 'some:uri';

        $localizedUri = new LocalizedUri($uri, 'en');

        $this->assertEquals($uri, $localizedUri->getUri());
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function language_can_be_retrieved()
    {
        $language = 'en_US';

        $localizedUri = new LocalizedUri('some:uri', $language);

        $this->assertEquals($language, $localizedUri->getLanguage());
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
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

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function deserializing_a_serialized_localized_uri_yields_an_equal_value_object()
    {
        $original = new LocalizedUri('some:uri', 'en');

        $deserialized = LocalizedUri::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     * @test
     * @group metadata
     * @group common
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notArray
     *
     * @param mixed $notArray
     */
    public function deserialization_requires_data_to_be_an_array($notArray)
    {
        $this->expectException(\InvalidArgumentException::class);
        LocalizedUri::deserialize($notArray);
    }

    /**
     * @test
     * @group metadata
     * @group common
     *
     * @dataProvider invalidDataProvider
     *
     * @param array $invalidData
     */
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

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function a_localized_uri_can_be_cast_to_string()
    {
        $this->assertIsString((string) new LocalizedUri('some:uri', 'en'));
    }
}
