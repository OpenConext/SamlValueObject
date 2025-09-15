<?php

namespace OpenConext\Value\Saml;

use OpenConext\Value\Exception\InvalidArgumentException;


class EntityTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @group entity
     */
    public function two_entities_with_the_same_entity_id_and_entity_type_are_equal()
    {
        $entityIdOne = new EntityId('one');
        $entityIdTwo = new EntityId('two');

        $sp  = EntityType::SP();
        $idp = EntityType::IdP();

        $base               = new Entity($entityIdOne, $sp);
        $theSame            = new Entity($entityIdOne, $sp);
        $differentType      = new Entity($entityIdOne, $idp);
        $differentId        = new Entity($entityIdTwo, $sp);
        $differentIdAndType = new Entity($entityIdTwo, $idp);

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($differentType));
        $this->assertFalse($base->equals($differentId));
        $this->assertFalse($base->equals($differentIdAndType));
    }

    /**
     * @test
     * @group entity
     *
     * @dataProvider invalidDescriptorProvider
     *
     * @param $invalidDescriptor
     */
    public function a_descriptor_must_have_exactly_two_elements_with_the_second_being_sp_or_idp($invalidDescriptor)
    {
        $this->expectException(InvalidArgumentException::class);
        Entity::fromDescriptor($invalidDescriptor);
    }

    public static function invalidDescriptorProvider()
    {
        return array (
            'no elements'           => array(array()),
            'one element'           => array(array('UM')),
            'three elements'        => array(array('UM', 'sp', 'third element')),
            'not sp or idp'         => array(array('UM', 'foobar')),
            'non-string entityId'   => array(array(1, 'sp')),
            'non-string entitytype' => array(array('UM', 1))
        );
    }

    /**
     * @test
     * @group entity
     */
    public function a_valid_descriptor_creates_an_entity()
    {
        $entity    = Entity::fromDescriptor(array('UM', 'sp'));
        $same      = new Entity(new EntityId('UM'), EntityType::SP());
        $otherType = Entity::fromDescriptor(array('UM', 'idp'));

        $this->assertTrue($entity->isServiceProvider());
        $this->assertTrue($entity->equals($same));
        $this->assertFalse($entity->equals($otherType));
    }

    /**
     * @test
     * @group entity
     */
    public function the_entity_id_of_an_entity_can_be_compared()
    {
        $entityId = new EntityId('OpenConext');
        $sameEntityId = new EntityId('OpenConext');
        $otherEntityId = new EntityId('Some Other Org');

        $entity = new Entity($entityId, EntityType::SP());

        $this->assertTrue($entity->hasEntityId($sameEntityId));
        $this->assertFalse($entity->hasEntityId($otherEntityId));
    }

    /**
     * @test
     * @group entity
     */
    public function an_entity_can_assert_which_type_it_is()
    {
        $sp = new Entity(new EntityId('OpenConext'), EntityType::SP());
        $idp = new Entity(new EntityId('OpenConext'), EntityType::IdP());

        $this->assertTrue($sp->isServiceProvider());
        $this->assertFalse($sp->isIdentityProvider());

        $this->assertTrue($idp->isIdentityProvider());
        $this->assertFalse($idp->isServiceProvider());
    }

    /**
     * @test
     * @group entity
     */
    public function the_entity_id_can_be_retrieved()
    {
        $entityId = new EntityId('OpenConext');
        $entity = new Entity($entityId, EntityType::SP());

        $this->assertTrue($entity->getEntityId()->equals($entityId));
    }

    /**
     * @test
     * @group entity
     */
    public function the_entity_type_can_be_retrieved()
    {
        $entityType = EntityType::SP();
        $entity = new Entity(new EntityId('OpenConext'), $entityType);

        $this->assertTrue($entity->getEntityType()->equals($entityType));
    }

    /**
     * @test
     * @group entity
     */
    public function deserializing_a_serialized_entity_results_in_an_equal_value_object()
    {
        $original     = new Entity(new EntityId('OpenConext.org'), EntityType::IdP());
        $deserialized = Entity::deserialize($original->serialize());

        $this->assertTrue($deserialized->equals($original));
    }

    /**
     * @test
     * @group        metadata
     *
     * @dataProvider invalidDeserializationDataProvider
     *
     * @param mixed $invalidData
     */
    public function deserialization_requires_valid_data($invalidData)
    {
        $this->expectException(\InvalidArgumentException::class);
        Entity::deserialize($invalidData);
    }

    /**
     * @return array
     */
    public static function invalidDeserializationDataProvider()
    {
        return array(
            'data is not an array'      => array('foobar'),
            'missing both keys'         => array(array('a')),
            'missing entity_id key'     => array('a' => 'foobar', 'entity_type' => 'saml20-sp'),
            'missing entity_type key'   => array('entity_id' => 'OpenConext.org'),
            'unknown entity_type value' => array('entity_id' => 'OpenConext.org', 'entity_type' => 'invalid'),
        );
    }

    /**
     * @test
     * @group entity
     */
    public function an_entity_can_be_cast_to_a_known_format_string()
    {
        $entityId = new EntityId('OpenConext');
        $entityType = EntityType::SP();

        $entity = new Entity($entityId, $entityType);
        $expected = sprintf('%s (%s)', $entityId, $entityType);

        $this->assertEquals($expected, (string) $entity);
    }
}
