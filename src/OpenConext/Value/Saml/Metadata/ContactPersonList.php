<?php

namespace OpenConext\Value\Saml\Metadata;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Exception\IndexOutOfBoundsException;
use OpenConext\Value\Serializable;

final class ContactPersonList implements Countable, IteratorAggregate, Serializable
{
    /**
     * @var ContactPerson[]
     */
    private $contactPersons;

    /**
     * @param ContactPerson[] $contactPersons
     */
    public function __construct(array $contactPersons)
    {
        Assertion::allIsInstanceOf($contactPersons, '\OpenConext\Value\Saml\Metadata\ContactPerson');

        $this->contactPersons = array_values($contactPersons);
    }

    /**
     * @param ContactPerson $contactPerson
     * @return ContactPersonList
     */
    public function add(ContactPerson $contactPerson)
    {
        return new self(array_merge($this->contactPersons, array($contactPerson)));
    }

    /**
     * @param ContactPerson $contactPerson
     * @return bool
     */
    public function contains(ContactPerson $contactPerson)
    {
        foreach ($this->contactPersons as $person) {
            if ($person->equals($contactPerson)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ContactPerson $contactPerson
     * @return int
     */
    public function indexOf(ContactPerson $contactPerson)
    {
        foreach ($this->contactPersons as $index => $person) {
            if ($person->equals($contactPerson)) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * @param int $index
     * @return ContactPerson
     */
    public function get($index)
    {
        Assertion::integer($index);

        if ($index < 0) {
            throw IndexOutOfBoundsException::tooLow($index, 0);
        }

        if ($index > count($this->contactPersons) - 1) {
            throw IndexOutOfBoundsException::tooHigh($index, count($this->contactPersons) - 1);
        }

        return $this->contactPersons[$index];
    }

    /**
     * @param Callable $predicate
     * @return null|ContactPerson
     */
    public function find($predicate)
    {
        Assertion::isCallable($predicate, null, 'predicate');

        foreach ($this->contactPersons as $contactPerson) {
            if (call_user_func($predicate, $contactPerson) === true) {
                return $contactPerson;
            }
        }

        return null;
    }

    /**
     * @param ContactPersonList $other
     * @return bool
     */
    public function equals(ContactPersonList $other)
    {
        if (count($this->contactPersons) !== count($other->contactPersons)) {
            return false;
        }

        foreach ($this->contactPersons as $index => $contactPerson) {
            if (!$contactPerson->equals($other->contactPersons[$index])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return ContactPerson[]
     */
    public function toArray()
    {
        return $this->contactPersons;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->contactPersons);
    }

    public function count()
    {
        return count($this->contactPersons);
    }

    public static function deserialize($data)
    {
        Assertion::isArray($data);

        $contactPersons = array_map(function ($contactPerson) {
            return ContactPerson::deserialize($contactPerson);
        }, $data);

        return new self($contactPersons);
    }

    public function serialize()
    {
        return array_map(function (ContactPerson $contactPerson) {
            return $contactPerson->serialize();
        }, $this->contactPersons);
    }

    public function __toString()
    {
        return sprintf('ContactPersonList[%s]', implode(', ', $this->contactPersons));
    }
}
