<?php

namespace OpenConext\Value;

use OpenConext\Value\Exception\InvalidArgumentException;

final class RegularExpression
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        if (!is_string($pattern) || trim($pattern) === '') {
            throw InvalidArgumentException::invalidType('non-blank string', 'pattern', $pattern);
        }

        if (!$this->isValidRegularExpression($pattern)) {
            throw new InvalidArgumentException(sprintf(
                'The pattern "%s" is not a valid regular expression',
                $pattern
            ));
        }

        $this->pattern = $pattern;
    }

    /**
     * @param string
     * @return bool
     */
    public function matches($string)
    {
        if (!is_string($string)) {
            throw InvalidArgumentException::invalidType('string', 'string', $string);
        }

        return preg_match($this->pattern, $string) === 1;
    }

    /**
     * @param RegularExpression $other
     * @return bool
     */
    public function equals(RegularExpression $other)
    {
        return $this->pattern === $other->pattern;
    }

    public function __toString()
    {
        return $this->pattern;
    }

    private function isValidRegularExpression($regularExpression)
    {
        $pregMatchErrored = false;
        set_error_handler(function () use (&$pregMatchErrored) {
            $pregMatchErrored = true;
        });

        preg_match($regularExpression, 'some test string');

        restore_error_handler();

        return !$pregMatchErrored && !preg_last_error();
    }
}
