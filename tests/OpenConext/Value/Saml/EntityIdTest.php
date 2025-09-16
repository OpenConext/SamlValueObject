<?php

namespace OpenConext\Value\Saml;

use OpenConext\Value\Exception\InvalidArgumentException;


class EntityIdTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     * @param mixed $invalidValue
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notStringOrEmptyString')]
    #[\PHPUnit\Framework\Attributes\Group('entity')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function only_non_empty_strings_are_valid_entity_ids($invalidValue)
    {
        $this->expectException(\InvalidArgumentException::class);
        new EntityId($invalidValue);
    }

    #[\PHPUnit\Framework\Attributes\Group('entity')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_same_entity_ids_are_considered_equal()
    {
        $base      = new EntityId('a');
        $theSame   = new EntityId('a');
        $different = new EntityId('A');

        $this->assertTrue($base->equals($theSame));
        $this->assertFalse($base->equals($different));
    }

    #[\PHPUnit\Framework\Attributes\Group('entity')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_given_entity_id_value_can_be_retrieved()
    {
        $entityIdValue = 'A';

        $entityId = new EntityId($entityIdValue);

        $this->assertSame($entityIdValue, $entityId->getEntityId());
    }

    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('entity')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserializing_a_serialized_entity_id_results_in_an_equal_value_object()
    {
        $original     = new EntityId('OpenConext.org');
        $deserialized = EntityId::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
    }

    /**
     *
     *
     * @param mixed $invalidData
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notStringOrEmptyString')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('entity')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserialization_requires_valid_data($invalidData)
    {
        $this->expectException(\InvalidArgumentException::class);
        EntityId::deserialize($invalidData);
    }

    #[\PHPUnit\Framework\Attributes\Group('entity')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function an_entity_id_can_be_cast_to_string()
    {
        $entityIdValue = 'OpenContextEntityID';

        $entityId = new EntityId($entityIdValue);

        $this->assertEquals($entityIdValue, (string) $entityId);
    }
}
