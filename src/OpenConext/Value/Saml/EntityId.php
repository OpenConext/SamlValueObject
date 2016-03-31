<?php

namespace OpenConext\Value\Saml;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Serializable;

final class EntityId implements Serializable
{
    /**
     * @var string
     */
    private $entityId;

    /**
     * @param string $entityId
     */
    public function __construct($entityId)
    {
        Assertion::nonEmptyString($entityId, 'entityId');

        $this->entityId = $entityId;
    }

    /**
     * @param EntityId $other
     * @return bool
     */
    public function equals(EntityId $other)
    {
        return $this->entityId === $other->entityId;
    }

    /**
     * @return string
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    public static function deserialize($data)
    {
        return new self($data);
    }

    public function serialize()
    {
        return $this->entityId;
    }

    public function __toString()
    {
        return $this->entityId;
    }
}
