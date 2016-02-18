<?php

namespace OpenConext\Value\Saml;

use PHPUnit_Framework_TestCase as UnitTest;

class EntityTypeTest extends UnitTest
{
    /**
     * @test
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
     *
     * @expectedException \OpenConext\Value\Exception\InvalidArgumentException
     */
    public function an_entity_type_cannot_be_created_with_a_non_existent_type()
    {
        new EntityType('This is not a valid EntityType');
    }
}
