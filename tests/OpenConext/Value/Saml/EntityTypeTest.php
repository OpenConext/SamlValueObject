<?php

namespace OpenConext\Value\Saml;

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
     * @expectedException \OpenConext\Value\Exception\InvalidArgumentException
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
