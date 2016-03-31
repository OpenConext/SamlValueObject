<?php

namespace OpenConext\Value;

use OpenConext\Value\Assert\Assertion;
use OpenConext\Value\Exception\InvalidArgumentException;

final class RegularExpression implements Serializable
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @param $regularExpression
     * @return bool
     */
    public static function isValidRegularExpression($regularExpression)
    {
        try {
            Assertion::validRegularExpression($regularExpression, 'regularExpression');
        } catch (InvalidArgumentException $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        Assertion::nonEmptyString($pattern, 'pattern');
        Assertion::validRegularExpression($pattern, 'pattern');

        $this->pattern = $pattern;
    }

    /**
     * @param string
     * @return bool
     */
    public function matches($string)
    {
        Assertion::string($string, 'String to match pattern against is not a string, "%s" given');

        return preg_match($this->pattern, $string) === 1;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param RegularExpression $other
     * @return bool
     */
    public function equals(RegularExpression $other)
    {
        return $this->pattern === $other->pattern;
    }

    public static function deserialize($data)
    {
        return new self($data);
    }

    public function serialize()
    {
        return $this->pattern;
    }

    public function __toString()
    {
        return $this->pattern;
    }
}
