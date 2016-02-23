<?php

namespace OpenConext\Value\Saml;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;

class EntityIdTest extends UnitTest
{
    /**
     * @test
     * @group entity
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     * @expectedException InvalidArgumentException
     *
     * @param mixed $invalidValue
     */
    public function only_non_empty_strings_are_valid_entity_ids($invalidValue)
    {
        new EntityId($invalidValue);
    }

    /**
     * @test
     * @group entity
     */
    public function the_same_entity_ids_are_considered_equal()
    {
        $base      = new EntityId('a');
        $theSame   = new EntityId('a');
        $different = new EntityId('A');

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($different));
    }

    /**
     * @test
     * @group entity
     */
    public function the_given_entity_id_value_can_be_retrieved()
    {
        $entityIdValue = 'A';

        $entityId = new EntityId($entityIdValue);

        $this->assertSame($entityIdValue, $entityId->getEntityId());
    }

    /**
     * @test
     * @group metadata
     * @group entity
     */
    public function deserializing_a_serialized_entity_id_results_in_an_equal_value_object()
    {
        $original     = new EntityId('OpenConext.org');
        $deserialized = EntityId::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     * @test
     * @group metadata
     * @group entity
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     * @expectedException InvalidArgumentException
     *
     * @param mixed $invalidData
     */
    public function deserialization_requires_valid_data($invalidData)
    {
        EntityId::deserialize($invalidData);
    }

    /**
     * @test
     * @group entity
     */
    public function an_entity_id_can_be_cast_to_string()
    {
        $entityIdValue = 'OpenContextEntityID';

        $entityId = new EntityId($entityIdValue);

        $this->assertEquals($entityIdValue, (string) $entityId);
    }
}
