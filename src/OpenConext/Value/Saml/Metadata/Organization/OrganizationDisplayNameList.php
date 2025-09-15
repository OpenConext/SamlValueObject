<?php

namespace OpenConext\Value\Saml\Metadata\Organization;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Exception\IndexOutOfBoundsException;
use OpenConext\Value\Serializable;

final class OrganizationDisplayNameList implements Countable, IteratorAggregate, Serializable
{
    /**
     * @var OrganizationDisplayName[]
     */
    private $organizationDisplayNames;

    /**
     * @param OrganizationDisplayName[] $organizationDisplayNames
     */
    public function __construct(array $organizationDisplayNames)
    {
        Assertion::allIsInstanceOf(
            $organizationDisplayNames,
            '\OpenConext\Value\Saml\Metadata\Organization\OrganizationDisplayName'
        );

        $this->organizationDisplayNames = array_values($organizationDisplayNames);
    }

    /**
     * @param OrganizationDisplayName $organizationDisplayName
     * @return OrganizationDisplayNameList
     */
    public function add(OrganizationDisplayName $organizationDisplayName)
    {
        return new self(array_merge($this->organizationDisplayNames, array($organizationDisplayName)));
    }

    /**
     * @param OrganizationDisplayName $organizationDisplayName
     * @return bool
     */
    public function contains(OrganizationDisplayName $organizationDisplayName)
    {
        foreach ($this->organizationDisplayNames as $displayName) {
            if ($displayName->equals($organizationDisplayName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param OrganizationDisplayName $organizationDisplayName
     * @return int
     */
    public function indexOf(OrganizationDisplayName $organizationDisplayName)
    {
        foreach ($this->organizationDisplayNames as $index => $displayName) {
            if ($displayName->equals($organizationDisplayName)) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * @param int $index
     * @return OrganizationDisplayName
     */
    public function get($index)
    {
        Assertion::integer($index);

        if ($index < 0) {
            throw IndexOutOfBoundsException::tooLow($index, 0);
        }

        if ($index > count($this->organizationDisplayNames) - 1) {
            throw IndexOutOfBoundsException::tooHigh($index, count($this->organizationDisplayNames) - 1);
        }

        return $this->organizationDisplayNames[$index];
    }

    /**
     * @param OrganizationDisplayNameList $other
     * @return bool
     */
    public function equals(OrganizationDisplayNameList $other)
    {
        if (count($this->organizationDisplayNames) !== count($other->organizationDisplayNames)) {
            return false;
        }

        foreach ($this->organizationDisplayNames as $index => $organizationDisplayName) {
            if (!$organizationDisplayName->equals($other->organizationDisplayNames[$index])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Callable $predicate
     * @return null|OrganizationDisplayName
     */
    public function find($predicate)
    {
        Assertion::isCallable($predicate, null, 'predicate');

        foreach ($this->organizationDisplayNames as $organizationDisplayName) {
            if (call_user_func($predicate, $organizationDisplayName) === true) {
                return $organizationDisplayName;
            }
        }

        return null;
    }

    /**
     * @return OrganizationDisplayName[]
     */
    public function toArray()
    {
        return $this->organizationDisplayNames;
    }

    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->organizationDisplayNames);
    }

    public function count(): int
    {
        return count($this->organizationDisplayNames);
    }

    public static function deserialize($data)
    {
        Assertion::isArray($data);

        $displayNames = array_map(function ($displayName) {
            return OrganizationDisplayName::deserialize($displayName);
        }, $data);

        return new self($displayNames);
    }

    public function serialize()
    {
        return array_map(function (OrganizationDisplayName $displayName) {
            return $displayName->serialize();
        }, $this->organizationDisplayNames);
    }

    public function __toString()
    {
        return sprintf('OrganizationDisplayNameList[%s]', implode(', ', $this->organizationDisplayNames));
    }
}
