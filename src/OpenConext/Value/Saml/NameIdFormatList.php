<?php

namespace OpenConext\Value\Saml;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Exception\IndexOutOfBoundsException;
use OpenConext\Value\Serializable;

final class NameIdFormatList implements Countable, IteratorAggregate, Serializable
{
    /**
     * @var NameIdFormat[]
     */
    private $nameIdFormats;

    /**
     * @param NameIdFormat[] $nameIdFormats
     */
    public function __construct(array $nameIdFormats)
    {
        Assertion::allIsInstanceOf($nameIdFormats, '\OpenConext\Value\Saml\NameIdFormat');

        $this->nameIdFormats = array_values($nameIdFormats);
    }

    /**
     * @param NameIdFormat $nameIdFormat
     * @return NameIdFormatList
     */
    public function add(NameIdFormat $nameIdFormat)
    {
        return new self(array_merge($this->nameIdFormats, array($nameIdFormat)));
    }

    /**
     * @param NameIdFormat $nameIdFormat
     * @return bool
     */
    public function contains(NameIdFormat $nameIdFormat)
    {
        foreach ($this->nameIdFormats as $format) {
            if ($format->equals($nameIdFormat)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param NameIdFormat $nameIdFormat
     * @return int
     */
    public function indexOf(NameIdFormat $nameIdFormat)
    {
        foreach ($this->nameIdFormats as $index => $format) {
            if ($format->equals($nameIdFormat)) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * @param int $index
     * @return NameIdFormat
     */
    public function get($index)
    {
        Assertion::integer($index);

        if ($index < 0) {
            throw IndexOutOfBoundsException::tooLow($index, 0);
        }

        if ($index > count($this->nameIdFormats) - 1) {
            throw IndexOutOfBoundsException::tooHigh($index, count($this->nameIdFormats) - 1);
        }

        return $this->nameIdFormats[$index];
    }

    /**
     * @param Callable $predicate
     * @return null|NameIdFormat
     */
    public function find($predicate)
    {
        Assertion::isCallable($predicate, null, 'predicate');

        foreach ($this->nameIdFormats as $nameIdFormat) {
            if (call_user_func($predicate, $nameIdFormat) === true) {
                return $nameIdFormat;
            }
        }

        return null;
    }

    /**
     * @param NameIdFormatList $other
     * @return bool
     */
    public function equals(NameIdFormatList $other)
    {
        if (count($this->nameIdFormats) !== count($other->nameIdFormats)) {
            return false;
        }

        foreach ($this->nameIdFormats as $index => $nameIdFormat) {
            if (!$nameIdFormat->equals($other->nameIdFormats[$index])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return NameIdFormat[]
     */
    public function toArray()
    {
        return $this->nameIdFormats;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->nameIdFormats);
    }

    public function count()
    {
        return count($this->nameIdFormats);
    }

    public static function deserialize($data)
    {
        Assertion::isArray($data);

        $nameIdFormats = array_map(function ($nameIdFormat) {
            return NameIdFormat::deserialize($nameIdFormat);
        }, $data);

        return new self($nameIdFormats);
    }

    public function serialize()
    {
        return array_map(function (NameIdFormat $nameIdFormat) {
            return $nameIdFormat->serialize();
        }, $this->nameIdFormats);
    }

    public function __toString()
    {
        return sprintf('NameIdFormatList[%s]', implode(', ', $this->nameIdFormats));
    }
}
