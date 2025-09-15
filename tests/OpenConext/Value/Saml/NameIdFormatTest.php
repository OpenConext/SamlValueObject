<?php

namespace OpenConext\Value\Saml;

use OpenConext\Value\Exception\InvalidArgumentException;


class NameIdFormatTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('nameid')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function name_id_formats_can_be_compared()
    {
        $transient  = NameIdFormat::transient();
        $persistent = NameIdFormat::persistent();

        $this->assertTrue($transient->equals(NameIdFormat::transient()));
        $this->assertFalse($transient->equals($persistent));
        $this->assertTrue($persistent->equals(NameIdFormat::persistent()));
        $this->assertFalse($persistent->equals(new NameIdFormat(NameIdFormat::EMAIL_ADDRESS)));
    }

    /**
     *
     *
     * @param string $nameIdFormat
     * @param string $factoryMethod
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('typeAndFactoryMethodProvider')]
    #[\PHPUnit\Framework\Attributes\Group('nameid')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function a_name_id_format_created_with_a_valid_format_equals_its_factory_created_version(
        $nameIdFormat,
        $factoryMethod
    ) {
        $nameIdFormatByType = new NameIdFormat($nameIdFormat);
        $nameIdFormatByFactory = NameIdFormat::$factoryMethod();

        $this->assertTrue($nameIdFormatByType->equals($nameIdFormatByFactory));
    }

    public static function typeAndFactoryMethodProvider()
    {
        return array(
            'unspecified'                   => array(NameIdFormat::UNSPECIFIED, 'unspecified'),
            'email address'                 => array(NameIdFormat::EMAIL_ADDRESS, 'emailAddress'),
            'X509 Subject Name'             => array(NameIdFormat::X509_SUBJECT_NAME, 'x509SubjectName'),
            'Kerberos Principal Name'       => array(NameIdFormat::KERBEROS_PRINCIPLE_NAME, 'kerberosPrincipalName'),
            'Entity Identifier'             => array(NameIdFormat::ENTITY_IDENTIFIER, 'entity'),
            'Persistent Identifier'         => array(NameIdFormat::PERSISTENT_IDENTIFIER, 'persistent'),
            'Transient Identifier'          => array(NameIdFormat::TRANSIENT_IDENTIFIER, 'transient'),
            'Windows Domain Qualified Name' => array(
                NameIdFormat::WINDOWS_DOMAIN_QUALIFIED_NAME,
                'windowsDomainQualifiedName'
            ),
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('nameid')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function an_invalid_format_causes_an_exception_to_be_thrown()
    {
        $this->expectException(\InvalidArgumentException::class);
        new NameIdFormat('This is very much not valid');
    }

    #[\PHPUnit\Framework\Attributes\Group('nameid')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_format_can_be_retrieved()
    {
        $nameIdFormat = NameIdFormat::unspecified();

        $this->assertEquals(NameIdFormat::UNSPECIFIED, $nameIdFormat->getFormat());
    }

    #[\PHPUnit\Framework\Attributes\Group('nameid')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserializing_a_serialized_name_id_format_results_in_an_equal_value_object()
    {
        $original     = NameIdFormat::x509SubjectName();
        $deserialized = NameIdFormat::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     *
     * @param mixed $invalidData
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notStringOrEmptyString')]
    #[\PHPUnit\Framework\Attributes\Group('nameid')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserialization_requires_valid_data($invalidData)
    {
        $this->expectException(\InvalidArgumentException::class);
        NameIdFormat::deserialize($invalidData);
    }

    #[\PHPUnit\Framework\Attributes\Group('nameid')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function a_name_id_format_can_be_cast_to_string()
    {
        $nameIdFormat = NameIdFormat::transient();

        $this->assertEquals(NameIdFormat::TRANSIENT_IDENTIFIER, (string) $nameIdFormat);
    }
}
