<?php

namespace OpenConext\Value\Saml;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Serializable;

final class Entity implements Serializable
{
    /**
     * @var EntityId
     */
    private $entityId;

    /**
     * @var EntityType
     */
    private $entityType;

    /**
     * @param array $descriptor array('entity-id', 'sp or idp')
     * @return Entity
     */
    public static function fromDescriptor(array $descriptor)
    {
        Assertion::count(
            $descriptor,
            2,
            'EntityDescriptor must be an array with two elements (both a string), the first must be the EntityId, the '
            . 'second the EntityType'
        );
        Assertion::inArray($descriptor[1], array('sp', 'idp'), 'Entity descriptor type is neither "sp" nor "idp"');

        $entityType = ($descriptor[1] === 'sp') ? EntityType::SP() : EntityType::IdP();

        return new Entity(new EntityId($descriptor[0]), $entityType);
    }

    public function __construct(EntityId $entityId, EntityType $entityType)
    {
        $this->entityId   = $entityId;
        $this->entityType = $entityType;
    }

    /**
     * @param EntityId $entityId
     * @return bool
     */
    public function hasEntityId(EntityId $entityId)
    {
        return $this->entityId->equals($entityId);
    }

    /**
     * @return bool
     */
    public function isServiceProvider()
    {
        return $this->entityType->isServiceProvider();
    }

    /**
     * @return bool
     */
    public function isIdentityProvider()
    {
        return $this->entityType->isIdentityProvider();
    }

    /**
     * @return EntityId
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @return EntityType
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * @param Entity $other
     * @return bool
     */
    public function equals(Entity $other)
    {
        return $this->entityId->equals($other->entityId) && $this->entityType->equals($other->entityType);
    }

    public static function deserialize($data)
    {
        Assertion::isArray($data);
        Assertion::keysExist($data, array('entity_id', 'entity_type'));

        return new self(EntityId::deserialize($data['entity_id']), EntityType::deserialize($data['entity_type']));
    }

    public function serialize()
    {
        return array(
            'entity_id' => $this->entityId->serialize(),
            'entity_type' => $this->entityType->serialize()
        );
    }

    public function __toString()
    {
        return sprintf('%s (%s)', $this->entityId, $this->entityType);
    }
}
