<?php

namespace OpenConext\Value\Saml;

use OpenConext\Value\Exception\InvalidArgumentException;


class EntityTypeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('entity')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_same_entity_types_are_considered_equal()
    {
        $base      = EntityType::SP();
        $theSame   = EntityType::SP();
        $different = EntityType::IdP();

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($different));
    }

    #[\PHPUnit\Framework\Attributes\Group('entity')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function an_entity_type_cannot_be_created_with_a_non_existent_type()
    {
        $this->expectException(\InvalidArgumentException::class);
        new EntityType('This is not a valid EntityType');
    }

    #[\PHPUnit\Framework\Attributes\Group('entity')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function an_entity_type_can_tell_what_it_is()
    {
        $sp = EntityType::SP();
        $idp = EntityType::IdP();

        $this->assertTrue($sp->isServiceProvider());
        $this->assertFalse($sp->isIdentityProvider());

        $this->assertTrue($idp->isIdentityProvider());
        $this->assertFalse($idp->isServiceProvider());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('entity')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserializing_a_serialized_entity_type_results_in_an_equal_value_object()
    {
        $original     = EntityType::SP();
        $deserialized = EntityType::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
        $this->assertTrue($deserialized->isServiceProvider());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('entity')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserialization_requires_valid_data()
    {
        $this->expectException(\InvalidArgumentException::class);
        EntityType::deserialize('not_a_valid_type');
    }

    #[\PHPUnit\Framework\Attributes\Group('entity')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function an_entity_type_can_be_cast_to_string()
    {
        $spAsString = (string) EntityType::SP();
        $idpAsString = (string) EntityType::IdP();

        $this->assertEquals(EntityType::TYPE_SP, $spAsString);
        $this->assertEquals(EntityType::TYPE_IDP, $idpAsString);
    }
}
