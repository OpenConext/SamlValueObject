<?php

namespace OpenConext\Value\Saml\Metadata\Organization;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Exception\IndexOutOfBoundsException;
use OpenConext\Value\Serializable;

final class OrganizationNameList implements Countable, IteratorAggregate, Serializable
{
    /**
     * @var OrganizationName[]
     */
    private $organizationNames;

    /**
     * @param OrganizationName[] $organizationNames
     */
    public function __construct(array $organizationNames)
    {
        Assertion::allIsInstanceOf($organizationNames, '\OpenConext\Value\Saml\Metadata\Organization\OrganizationName');

        $this->organizationNames = array_values($organizationNames);
    }

    /**
     * @param OrganizationName $organizationName
     * @return OrganizationNameList
     */
    public function add(OrganizationName $organizationName)
    {
        return new self(array_merge($this->organizationNames, array($organizationName)));
    }

    /**
     * @param OrganizationName $organizationName
     * @return bool
     */
    public function contains(OrganizationName $organizationName)
    {
        foreach ($this->organizationNames as $name) {
            if ($name->equals($organizationName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param OrganizationName $organizationName
     * @return int
     */
    public function indexOf(OrganizationName $organizationName)
    {
        foreach ($this->organizationNames as $index => $name) {
            if ($name->equals($organizationName)) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * @param int $index
     * @return OrganizationName
     */
    public function get($index)
    {
        Assertion::integer($index);

        if ($index < 0) {
            throw IndexOutOfBoundsException::tooLow($index, 0);
        }

        if ($index > count($this->organizationNames) - 1) {
            throw IndexOutOfBoundsException::tooHigh($index, count($this->organizationNames) - 1);
        }

        return $this->organizationNames[$index];
    }

    /**
     * @param Callable $predicate
     * @return null|OrganizationName
     */
    public function find($predicate)
    {
        Assertion::isCallable($predicate, null, 'predicate');

        foreach ($this->organizationNames as $organizationName) {
            if (call_user_func($predicate, $organizationName) === true) {
                return $organizationName;
            }
        }

        return null;
    }

    /**
     * @param OrganizationNameList $other
     * @return bool
     */
    public function equals(OrganizationNameList $other)
    {
        if (count($this->organizationNames) !== count($other->organizationNames)) {
            return false;
        }

        foreach ($this->organizationNames as $index => $organizationName) {
            if (!$organizationName->equals($other->organizationNames[$index])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return OrganizationName[]
     */
    public function toArray()
    {
        return $this->organizationNames;
    }

    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->organizationNames);
    }

    public function count(): int
    {
        return count($this->organizationNames);
    }

    public static function deserialize($data)
    {
        Assertion::isArray($data);

        $names = array_map(function ($name) {
            return OrganizationName::deserialize($name);
        }, $data);

        return new self($names);
    }

    public function serialize()
    {
        return array_map(function (OrganizationName $name) {
            return $name->serialize();
        }, $this->organizationNames);
    }

    public function __toString()
    {
        return sprintf('OrganizationNameList[%s]', implode(', ', $this->organizationNames));
    }
}
