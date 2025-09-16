<?php

namespace OpenConext\Value\Saml;

use ArrayIterator;
use Assert\Assertion;
use Countable;
use IteratorAggregate;
use OpenConext\Value\Serializable;
use Traversable;

final class EntitySet implements Countable, IteratorAggregate, Serializable
{
    /**
     * @var Entity[]
     */
    private $entities = array();

    /**
     * @param Entity[] $entities
     */
    public function __construct(array $entities = array())
    {
        Assertion::allIsInstanceOf($entities, '\OpenConext\Value\Saml\Entity');

        foreach ($entities as $entity) {
            if ($this->contains($entity)) {
                continue;
            }

            $this->entities[] = $entity;
        }
    }

    /**
     * @param Entity $entity The entity to search for.
     * @return boolean 'true' if the collection contains the element, 'false' otherwise.
     */
    public function contains(Entity $entity)
    {
        foreach ($this->entities as $existingEntity) {
            if ($entity->equals($existingEntity)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param EntitySet $other
     * @return bool
     */
    public function equals(EntitySet $other)
    {
        if (count($this->entities) !== count($other->entities)) {
            return false;
        }

        foreach ($this->entities as $entity) {
            if (!$other->contains($entity)) {
                return false;
            }
        }

        return true;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->entities);
    }

    public function count(): int
    {
        return count($this->entities);
    }

    public static function deserialize($data)
    {
        Assertion::isArray($data);

        $entities = array_map(function ($entity) {
            return Entity::deserialize($entity);
        }, $data);

        return new self($entities);
    }

    public function serialize()
    {
        return array_map(function (Entity $entity) {
            return $entity->serialize();
        }, $this->entities);
    }

    public function __toString()
    {
        return sprintf('EntitySet["%s"]', implode('", "', $this->entities));
    }
}
