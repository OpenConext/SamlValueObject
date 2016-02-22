<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OpenConext\Value\Exception\IndexOutOfBoundsException;
use OpenConext\Value\Exception\InvalidArgumentException;

final class EmailAddressList implements Countable, IteratorAggregate
{
    /**
     * @var EmailAddress[]
     */
    private $emailAddresses;

    public function __construct(array $emailAddresses)
    {
        foreach ($emailAddresses as $index => $emailAddress) {
            if (!$emailAddress instanceof EmailAddress) {
                throw new InvalidArgumentException(sprintf(
                    'Unexpected value found when creating EmailAddressList at index "%d": "%s"',
                    $index,
                    (is_object($emailAddress) ? get_class($emailAddress) : gettype($emailAddress))
                ));
            }

            $this->emailAddresses[] = $emailAddress;
        }
    }

    /**
     * @param EmailAddress $emailAddress
     * @return EmailAddressList
     */
    public function add(EmailAddress $emailAddress)
    {
        return new self(array_merge($this->emailAddresses, array($emailAddress)));
    }

    /**
     * @param EmailAddress $emailAddress
     * @return bool
     */
    public function contains(EmailAddress $emailAddress)
    {
        foreach ($this->emailAddresses as $address) {
            if ($address->equals($emailAddress)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param EmailAddress $emailAddress
     * @return int
     */
    public function indexOf(EmailAddress $emailAddress)
    {
        foreach ($this->emailAddresses as $index => $address) {
            if ($address->equals($emailAddress)) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * @param int $index
     * @return EmailAddress
     */
    public function get($index)
    {
        if (!is_int($index)) {
            throw InvalidArgumentException::invalidType('integer', 'index', $index);
        }

        if ($index < 0) {
            throw IndexOutOfBoundsException::tooLow($index, 0);
        }

        if ($index > count($this->emailAddresses) - 1) {
            throw IndexOutOfBoundsException::tooHigh($index, count($this->emailAddresses) - 1);
        }

        return $this->emailAddresses[$index];
    }

    /**
     * @param EmailAddressList $other
     * @return bool
     */
    public function equals(EmailAddressList $other)
    {
        if (count($this->emailAddresses) !== count($other->emailAddresses)) {
            return false;
        }

        foreach ($this->emailAddresses as $index => $emailAddress) {
            if (!$emailAddress->equals($other->emailAddresses[$index])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return EmailAddress[]
     */
    public function toArray()
    {
        return $this->emailAddresses;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->emailAddresses);
    }

    public function count()
    {
        return count($this->emailAddresses);
    }

    public function __toString()
    {
        return sprintf('EmailAddressList[%s]', implode(', ', $this->emailAddresses));
    }
}
