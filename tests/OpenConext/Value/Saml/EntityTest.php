<?php

namespace OpenConext\Value\Saml;

use PHPUnit_Framework_TestCase as UnitTest;

class EntityTest extends UnitTest
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
     * @expectedException \OpenConext\Value\Exception\InvalidArgumentException
     *
     * @param $invalidDescriptor
     */
    public function a_descriptor_must_have_exactly_two_elements_with_the_second_being_sp_or_idp($invalidDescriptor)
    {
        Entity::fromDescriptor($invalidDescriptor);
    }

    public function invalidDescriptorProvider()
    {
        return array (
            'no elements'    => array(array()),
            'one element'    => array(array('UM')),
            'three elements' => array(array('UM', 'sp', 'ermagerd')),
            'not sp or idp'  => array(array('UM', 'foobar'))
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
}
