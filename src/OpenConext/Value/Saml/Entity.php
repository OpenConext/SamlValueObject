<?php

namespace OpenConext\Value\Saml;

use OpenConext\Value\Exception\InvalidArgumentException;

final class Entity
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
        if (count($descriptor) !== 2) {
            throw new InvalidArgumentException(
                'EntityDescriptor must be an array with two elements (both a string), the first must be the EntityId,'
                . ' the second the EntityType'
            );
        }

        switch ($descriptor[1]) {
            case 'sp':
                return new Entity(new EntityId($descriptor[0]), EntityType::SP());
            case 'idp':
                return new Entity(new EntityId($descriptor[0]), EntityType::IdP());
            default:
                throw new InvalidArgumentException('Entity descriptor type is neither "sp" nor "idp"');
        }
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

    public function __toString()
    {
        return sprintf('%s (%s)', $this->entityId, $this->entityType);
    }
}
