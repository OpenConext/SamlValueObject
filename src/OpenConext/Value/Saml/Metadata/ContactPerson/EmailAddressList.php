<?php

namespace OpenConext\Value\Saml\Metadata\ContactPerson;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Exception\IndexOutOfBoundsException;
use OpenConext\Value\Serializable;

final class EmailAddressList implements Countable, IteratorAggregate, Serializable
{
    /**
     * @var EmailAddress[]
     */
    private $emailAddresses;

    /**
     * @param EmailAddress[] $emailAddresses
     */
    public function __construct(array $emailAddresses)
    {
        Assertion::allIsInstanceOf($emailAddresses, '\OpenConext\Value\Saml\Metadata\ContactPerson\EmailAddress');

        $this->emailAddresses = array_values($emailAddresses);
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
        Assertion::integer($index);

        if ($index < 0) {
            throw IndexOutOfBoundsException::tooLow($index, 0);
        }

        if ($index > count($this->emailAddresses) - 1) {
            throw IndexOutOfBoundsException::tooHigh($index, count($this->emailAddresses) - 1);
        }

        return $this->emailAddresses[$index];
    }

    /**
     * @param Callable $predicate
     * @return null|EmailAddress
     */
    public function find($predicate)
    {
        Assertion::isCallable($predicate, null, 'predicate');

        foreach ($this->emailAddresses as $emailAddress) {
            if (call_user_func($predicate, $emailAddress) === true) {
                return $emailAddress;
            }
        }

        return null;
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

    public static function deserialize($data)
    {
        Assertion::isArray($data);

        $emailAddresses = array_map(function ($emailAddress) {
            return EmailAddress::deserialize($emailAddress);
        }, $data);

        return new self($emailAddresses);
    }

    public function serialize()
    {
        return array_map(function (EmailAddress $emailAddress) {
            return $emailAddress->serialize();
        }, $this->emailAddresses);
    }

    public function __toString()
    {
        return sprintf('EmailAddressList[%s]', implode(', ', $this->emailAddresses));
    }
}
