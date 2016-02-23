<?php

namespace OpenConext\Value\Saml;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;

class EntityTypeTest extends UnitTest
{
    /**
     * @test
     * @group entity
     */
    public function the_same_entity_types_are_considered_equal()
    {
        $base      = EntityType::SP();
        $theSame   = EntityType::SP();
        $different = EntityType::IdP();

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($different));
    }

    /**
     * @test
     * @group entity
     *
     * @expectedException InvalidArgumentException
     */
    public function an_entity_type_cannot_be_created_with_a_non_existent_type()
    {
        new EntityType('This is not a valid EntityType');
    }

    /**
     * @test
     * @group entity
     */
    public function an_entity_type_can_tell_what_it_is()
    {
        $sp = EntityType::SP();
        $idp = EntityType::IdP();

        $this->assertTrue($sp->isServiceProvider());
        $this->assertFalse($sp->isIdentityProvider());

        $this->assertTrue($idp->isIdentityProvider());
        $this->assertFalse($idp->isServiceProvider());
    }

    /**
     * @test
     * @group metadata
     * @group entity
     */
    public function deserializing_a_serialized_entity_type_results_in_an_equal_value_object()
    {
        $original     = EntityType::SP();
        $deserialized = EntityType::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
        $this->assertTrue($deserialized->isServiceProvider());
    }

    /**
     * @test
     * @group metadata
     * @group entity
     *
     * @expectedException InvalidArgumentException
     */
    public function deserialization_requires_valid_data()
    {
        EntityType::deserialize('not_a_valid_type');
    }

    /**
     * @test
     * @group entity
     */
    public function an_entity_type_can_be_cast_to_string()
    {
        $spAsString = (string) EntityType::SP();
        $idpAsString = (string) EntityType::IdP();

        $this->assertEquals(EntityType::TYPE_SP, $spAsString);
        $this->assertEquals(EntityType::TYPE_IDP, $idpAsString);
    }
}
