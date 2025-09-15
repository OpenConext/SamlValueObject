<?php

namespace OpenConext\Value\Exception;

use Assert\InvalidArgumentException as InvalidAssertionException;

class InvalidArgumentException extends InvalidAssertionException implements OpenConextValueException
{
    // according to CS used, propertypath and value should be switched, but that breaks the integration with the library
    // @codingStandardsIgnoreStart
    public function __construct($message, $code, $propertyPath = null, $value = null, array $constraints = array())
    {
        // @codingStandardsIgnoreEnd
        if ($propertyPath !== null && strpos($message, $propertyPath) === false) {
            $message = sprintf('Invalid argument given for "%s": %s', $propertyPath, $message);
        }

        parent::__construct($message, $code, $propertyPath, $value, $constraints);
    }
}
