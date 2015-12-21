<?php

namespace OpenConext\Value\Saml;

use PHPUnit_Framework_TestCase as UnitTest;

class EntityIdTest extends UnitTest
{
    /**
     * @param mixed $invalidValue
     *
     * @test
     * @group        saml
     * @dataProvider \OpenConext\Value\TestDataProvider::notEmptyString
     *
     * @expectedException \OpenConext\Value\Exception\InvalidArgumentException
     */
    public function only_non_empty_strings_are_valid_entity_ids($invalidValue)
    {
        new EntityId($invalidValue);
    }

    /**
     * @test
     * @group saml
     */
    public function the_same_entity_ids_are_considered_equal()
    {
        $base      = new EntityId('a');
        $theSame   = new EntityId('a');
        $different = new EntityId('A');

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($different));
    }
}
