<?php

namespace OpenConext\Value\Saml;

use OpenConext\Value\Exception\InvalidArgumentException;

final class EntityId
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
        if (!is_string($entityId) || trim($entityId) === '') {
            throw InvalidArgumentException::invalidType('non-blank string', 'entityId', $entityId);
        }

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

    public function __toString()
    {
        return $this->entityId;
    }
}
