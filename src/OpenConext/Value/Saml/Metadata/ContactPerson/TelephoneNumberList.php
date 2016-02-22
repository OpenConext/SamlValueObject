<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OpenConext\Value\Exception\IndexOutOfBoundsException;
use OpenConext\Value\Exception\InvalidArgumentException;

final class TelephoneNumberList implements Countable, IteratorAggregate
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
        foreach ($telephoneNumbers as $index => $telephoneNumber) {
            if (!$telephoneNumber instanceof TelephoneNumber) {
                throw new InvalidArgumentException(sprintf(
                    'Unexpected value found when creating TelephoneNumberList at index "%d": "%s"',
                    $index,
                    (is_object($telephoneNumber) ? get_class($telephoneNumber) : gettype($telephoneNumber))
                ));
            }

            $this->telephoneNumbers[] = $telephoneNumber;
        }
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
        if (!is_int($index)) {
            throw InvalidArgumentException::invalidType('integer', 'index', $index);
        }

        if ($index < 0) {
            throw IndexOutOfBoundsException::tooLow($index, 0);
        }

        if ($index > count($this->telephoneNumbers) - 1) {
            throw IndexOutOfBoundsException::tooHigh($index, count($this->telephoneNumbers) - 1);
        }

        return $this->telephoneNumbers[$index];
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

    public function getIterator()
    {
        return new ArrayIterator($this->telephoneNumbers);
    }

    public function count()
    {
        return count($this->telephoneNumbers);
    }

    public function __toString()
    {
        return sprintf('TelephoneNumberList[%s]', implode(', ', $this->telephoneNumbers));
    }
}
