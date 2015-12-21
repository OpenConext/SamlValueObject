<?php

namespace OpenConext\Value\Saml;

use ArrayIterator;
use Countable;
use IteratorAggregate;

final class EntitySet implements Countable, IteratorAggregate
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
        foreach ($entities as $entity) {
            $this->initializeWith($entity);
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

    public function getIterator()
    {
        return new ArrayIterator($this->entities);
    }

    public function count()
    {
        return count($this->entities);
    }

    /**
     * @param Entity $entity
     */
    private function initializeWith(Entity $entity)
    {
        if ($this->contains($entity)) {
            return;
        }

        $this->entities[] = $entity;
    }
}
