<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use OpenConext\Value\Exception\InvalidArgumentException;


class ContactTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function contact_types_can_be_compared()
    {
        $administrative = ContactType::administrative();
        $technical = ContactType::technical();

        $this->assertTrue($administrative->equals(ContactType::administrative()));
        $this->assertFalse($administrative->equals($technical));
        $this->assertTrue($technical->equals(ContactType::technical()));
        $this->assertFalse($technical->equals(ContactType::other()));
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     *
     * @dataProvider typeAndFactoryMethodProvider
     *
     * @param string $contactType
     * @param string $factoryMethod
     */
    public function a_contact_type_created_with_a_valid_type_equals_its_factory_created_version(
        $contactType,
        $factoryMethod
    ) {
        $contactByType = new ContactType($contactType);
        $contactByFactory = ContactType::$factoryMethod();

        $this->assertTrue($contactByType->equals($contactByFactory));
    }

    public static function typeAndFactoryMethodProvider()
    {
        return array(
            'administrative' => array(ContactType::TYPE_ADMINISTRATIVE, 'administrative'),
            'billing'        => array(ContactType::TYPE_BILLING, 'billing'),
            'support'        => array(ContactType::TYPE_SUPPORT, 'support'),
            'technical'      => array(ContactType::TYPE_TECHNICAL, 'technical'),
            'other'          => array(ContactType::TYPE_OTHER, 'other')
        );
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function an_invalid_type_causes_an_exception_to_be_thrown()
    {
        $this->expectException(\InvalidArgumentException::class);
        new ContactType('Nope, not a valid contacttype');
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function the_type_definition_can_be_retrieved()
    {
        $type = ContactType::technical();

        $this->assertEquals(ContactType::TYPE_TECHNICAL, $type->getContactType());
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function deserializing_a_serialized_contact_type_results_in_an_equal_value_object()
    {
        $original     = ContactType::administrative();
        $deserialized = ContactType::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
        $this->assertEquals(ContactType::TYPE_ADMINISTRATIVE, $deserialized->getContactType());
    }

    /**
     * @test
     * @group        metadata
     * @group        contactperson
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     *
     * @param mixed $invalidData
     */
    public function deserialization_requires_valid_data($invalidData)
    {
        $this->expectException(\InvalidArgumentException::class);
        ContactType::deserialize($invalidData);
    }

    /**
     * @test
     * @group metadata
     * @group contactperson
     */
    public function a_contact_type_can_be_cast_to_string()
    {
        $type = ContactType::technical();

        $this->assertEquals(ContactType::TYPE_TECHNICAL, (string) $type);
    }
}
