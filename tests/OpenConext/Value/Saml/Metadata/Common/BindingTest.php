<?php

namespace OpenConext\Value\Saml\Metadata\Common;

use OpenConext\Value\Exception\InvalidArgumentException;


class BindingTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @group metadata
     * @group common
     */
    public function binding_must_be_one_of_the_valid_bindings()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Binding('not a valid binding :(');
    }

    /**
     * @test
     * @group metadata
     * @group common
     *
     * @dataProvider bindingAndFactoryMethodProvider
     *
     * @param string $binding
     * @param string $factoryMethod
     */
    public function bindings_created_through_constructor_are_equal_to_their_factory_method_counterparts(
        $binding,
        $factoryMethod
    ) {
        $byConstructor = new Binding($binding);
        $byFactoryMethod = Binding::$factoryMethod();

        $this->assertTrue($byConstructor->equals($byFactoryMethod));
    }

    /**
     * @return array
     */
    public static function bindingAndFactoryMethodProvider()
    {
        return array(
            'HTTP_POST'     => array(Binding::HTTP_POST, 'httpPost'),
            'HTTP_REDIRECT' => array(Binding::HTTP_REDIRECT, 'httpRedirect'),
            'HTTP_ARTIFACT' => array(Binding::HTTP_ARTIFACT, 'httpArtifact'),
            'SOAP'          => array(Binding::SOAP, 'soap'),
            'HOK_SSO'       => array(Binding::HOK_SSO, 'holderOfKey')
        );
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function a_textual_binding_can_be_tested_for_validity()
    {
        $this->assertFalse(Binding::isValidBinding('not a valid binding'));
        $this->assertTrue(Binding::isValidBinding(Binding::HTTP_POST));
        $this->assertTrue(Binding::isValidBinding(Binding::HTTP_REDIRECT));
        $this->assertTrue(Binding::isValidBinding(Binding::HTTP_ARTIFACT));
        $this->assertTrue(Binding::isValidBinding(Binding::SOAP));
        $this->assertTrue(Binding::isValidBinding(Binding::HOK_SSO));
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function the_type_of_a_binding_can_be_verified()
    {
        $binding = Binding::holderOfKey();

        $this->assertTrue($binding->isOfType(Binding::HOK_SSO));
        $this->assertFalse($binding->isOfType(Binding::SOAP));
    }

    /**
     * @test
     * @group        metadata
     * @group        common
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     *
     * @param mixed $notStringOrEmptyString
     */
    public function to_verify_the_type_of_a_binding_a_non_empty_string_must_be_used($notStringOrEmptyString)
    {
        $this->expectException(\InvalidArgumentException::class);
        $binding = Binding::httpPost();
        $binding->isOfType($notStringOrEmptyString);
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function binding_can_be_retrieved()
    {
        $byConstructor = new Binding(Binding::HTTP_POST);
        $byFactory = Binding::httpPost();

        $this->assertEquals(Binding::HTTP_POST, $byConstructor->getBinding());
        $this->assertEquals(Binding::HTTP_POST, $byFactory->getBinding());
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function bindings_are_equal_if_they_are_of_the_same_type()
    {
        $base = Binding::httpPost();
        $same = Binding::httpPost();
        $different = Binding::httpRedirect();

        $this->assertTrue($base->equals($same));
        $this->assertFalse($base->equals($different));
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function deserializing_a_serialized_binding_yields_an_equal_value_object()
    {
        $original = Binding::httpPost();

        $deserialized = Binding::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
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
    public function deserialization_requires_data_to_be_a_non_empty_string($notStringOrEmtpyString)
    {
        $this->expectException(\InvalidArgumentException::class);
        Binding::deserialize($notStringOrEmtpyString);
    }

    /**
     * @test
     * @group metadata
     * @group common
     */
    public function a_binding_can_be_cast_to_string()
    {
        $this->assertIsString((string) Binding::HTTP_ARTIFACT);
    }
}
