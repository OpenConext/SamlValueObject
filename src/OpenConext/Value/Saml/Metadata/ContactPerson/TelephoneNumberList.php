<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Exception\IndexOutOfBoundsException;
use OpenConext\Value\Serializable;

final class TelephoneNumberList implements Countable, IteratorAggregate, Serializable
{
    /**
     * @var TelephoneNumber[]
     */
    private $telephoneNumbers;

    /**
     * @param TelephoneNumber[] $telephoneNumbers
     */
    public function __construct(array $telephoneNumbers)
    {
        Assertion::allIsInstanceOf($telephoneNumbers, '\OpenConext\Value\Saml\Metadata\ContactPerson\TelephoneNumber');

        $this->telephoneNumbers = array_values($telephoneNumbers);
    }

    /**
     * @param TelephoneNumber $telephoneNumber
     * @return TelephoneNumberList
     */
    public function add(TelephoneNumber $telephoneNumber)
    {
        return new self(array_merge($this->telephoneNumbers, array($telephoneNumber)));
    }

    /**
     * @param TelephoneNumber $telephoneNumber
     * @return bool
     */
    public function contains(TelephoneNumber $telephoneNumber)
    {
        foreach ($this->telephoneNumbers as $number) {
            if ($number->equals($telephoneNumber)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param TelephoneNumber $telephoneNumber
     * @return int
     */
    public function indexOf(TelephoneNumber $telephoneNumber)
    {
        foreach ($this->telephoneNumbers as $index => $number) {
            if ($number->equals($telephoneNumber)) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * @param int $index
     * @return TelephoneNumber
     */
    public function get($index)
    {
        Assertion::integer($index);

        if ($index < 0) {
            throw IndexOutOfBoundsException::tooLow($index, 0);
        }

        if ($index > count($this->telephoneNumbers) - 1) {
            throw IndexOutOfBoundsException::tooHigh($index, count($this->telephoneNumbers) - 1);
        }

        return $this->telephoneNumbers[$index];
    }

    /**
     * @param Callable $predicate
     * @return null|TelephoneNumber
     */
    public function find($predicate)
    {
        Assertion::isCallable($predicate, null, 'predicate');

        foreach ($this->telephoneNumbers as $telephoneNumber) {
            if (call_user_func($predicate, $telephoneNumber) === true) {
                return $telephoneNumber;
            }
        }

        return null;
    }

    /**
     * @param TelephoneNumberList $other
     * @return bool
     */
    public function equals(TelephoneNumberList $other)
    {
        if (count($this->telephoneNumbers) !== count($other->telephoneNumbers)) {
            return false;
        }

        foreach ($this->telephoneNumbers as $index => $telephoneNumber) {
            if (!$telephoneNumber->equals($other->telephoneNumbers[$index])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return TelephoneNumber[]
     */
    public function toArray()
    {
        return $this->telephoneNumbers;
    }

    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->telephoneNumbers);
    }

    public function count(): int
    {
        return count($this->telephoneNumbers);
    }

    public static function deserialize($data)
    {
        Assertion::isArray($data);

        $telephoneNumbers = array_map(function ($telephoneNumber) {
            return TelephoneNumber::deserialize($telephoneNumber);
        }, $data);

        return new self($telephoneNumbers);
    }

    public function serialize()
    {
        return array_map(function (TelephoneNumber $telephoneNumber) {
            return $telephoneNumber->serialize();
        }, $this->telephoneNumbers);
    }

    public function __toString()
    {
        return sprintf('TelephoneNumberList[%s]', implode(', ', $this->telephoneNumbers));
    }
}
