<?php

namespace OpenConext\Value\Saml\Metadata\Common;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;

class LocalizedNameTest extends UnitTest
{
    /**
     * @test
     * @group metadata
     * @group common
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     * @expectedException InvalidArgumentException
     *
     * @param mixed $notStringOrEmtpyString
     */
    public function name_must_be_a_non_empty_string($notStringOrEmtpyString)
    {
        new LocalizedName($notStringOrEmtpyString, 'en');
    }

    /**
     * @test
     * @group metadata
     * @group common
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     * @expectedException InvalidArgumentException
     *
     * @param mixed $notStringOrEmtpyString
     */
    public function language_must_be_a_non_empty_string($notStringOrEmtpyString)
    {
        new LocalizedName('OpenConext', $notStringOrEmtpyString);
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function name_can_be_retrieved()
    {
        $name = 'OpenConext';

        $localizedName = new LocalizedName($name, 'en');

        $this->assertEquals($name, $localizedName->getName());
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function language_can_be_retrieved()
    {
        $language = 'en_US';

        $localizedName = new LocalizedName('OpenConext', $language);

        $this->assertEquals($language, $localizedName->getLanguage());
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
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

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function deserializing_a_serialized_localized_name_yields_an_equal_value_object()
    {
        $original = new LocalizedName('OpenConext', 'en');

        $deserialized = LocalizedName::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     * @test
     * @group metadata
     * @group common
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notArray
     * @expectedException InvalidArgumentException
     *
     * @param mixed $notArray
     */
    public function deserialization_requires_data_to_be_an_array($notArray)
    {
        LocalizedName::deserialize($notArray);
    }

    /**
     * @test
     * @group metadata
     * @group common
     *
     * @dataProvider invalidDataProvider
     * @expectedException InvalidArgumentException
     *
     * @param array $invalidData
     */
    public function deserialization_requires_all_required_keys_to_be_present($invalidData)
    {
        LocalizedName::deserialize($invalidData);
    }

    public function invalidDataProvider()
    {
        return array(
            'no matching keys' => array(array('foo' => 'OpenConext', 'bar' => 'en_US')),
            'no name'          => array(array('language' => 'en_US')),
            'no language'      => array(array('name' => 'OpenConext')),
        );
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function a_localized_name_can_be_cast_to_string()
    {
        $this->assertInternalType('string', (string) new LocalizedName('OpenConext', 'en'));
    }
}
