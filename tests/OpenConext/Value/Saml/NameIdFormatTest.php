<?php

namespace OpenConext\Value\Saml;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;

class ContactTypeTest extends UnitTest
{
    /**
     * @test
     * @group nameid
     */
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
     * @test
     * @group nameid
     *
     * @dataProvider typeAndFactoryMethodProvider
     *
     * @param string $nameIdFormat
     * @param string $factoryMethod
     */
    public function a_name_id_format_created_with_a_valid_format_equals_its_factory_created_version(
        $nameIdFormat,
        $factoryMethod
    ) {
        $nameIdFormatByType = new NameIdFormat($nameIdFormat);
        $nameIdFormatByFactory = NameIdFormat::$factoryMethod();

        $nameIdFormatByType->equals($nameIdFormatByFactory);
    }

    public function typeAndFactoryMethodProvider()
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

    /**
     * @test
     * @group nameid
     *
     * @expectedException InvalidArgumentException
     */
    public function an_invalid_format_causes_an_exception_to_be_thrown()
    {
        new NameIdFormat('This is very much not valid');
    }

    /**
     * @test
     * @group nameid
     */
    public function the_format_can_be_retrieved()
    {
        $nameIdFormat = NameIdFormat::unspecified();

        $this->assertEquals(NameIdFormat::UNSPECIFIED, $nameIdFormat->getFormat());
    }

    /**
     * @test
     * @group nameid
     */
    public function deserializing_a_serialized_name_id_format_results_in_an_equal_value_object()
    {
        $original     = NameIdFormat::x509SubjectName();
        $deserialized = NameIdFormat::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     * @test
     * @group nameid
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     * @expectedException InvalidArgumentException
     *
     * @param mixed $invalidData
     */
    public function deserialization_requires_valid_data($invalidData)
    {
        NameIdFormat::deserialize($invalidData);
    }

    /**
     * @test
     * @group nameid
     */
    public function a_name_id_format_can_be_cast_to_string()
    {
        $nameIdFormat = NameIdFormat::transient();

        $this->assertEquals(NameIdFormat::TRANSIENT_IDENTIFIER, (string) $nameIdFormat);
    }
}
