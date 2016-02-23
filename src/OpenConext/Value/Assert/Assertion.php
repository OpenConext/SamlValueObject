<?php

namespace OpenConext\Value\Assert;

use Assert\Assertion as BaseAssertion;

/**
 * @method static void nullOrNonEmptyString($value, $message = null, $propertyPath = null)
 * @method static void allNonEmptyString($value, $message = null, $propertyPath = null)
 * @method static void allValidRegularExpression($value, $message = null, $propertyPath = null)
 */
class Assertion extends BaseAssertion
{
    const INVALID_NON_EMPTY_STRING   = 501;
    const INVALID_REGULAR_EXPRESSION = 502;

    protected static $exceptionClass = 'OpenConext\Value\Exception\InvalidArgumentException';

    /**
     * @param string $value
     * @param string $propertyPath
     * @return void
     */
    public static function nonEmptyString($value, $propertyPath)
    {
        if (!is_string($value) || trim($value) === '') {
            $message = 'Expected non-empty string for "%s", "%s" given';

            throw static::createException(
                $value,
                sprintf($message, $propertyPath, static::stringify($value)),
                static::INVALID_NON_EMPTY_STRING,
                $propertyPath
            );
        }
    }

    /**
     * @param $regularExpression
     * @param $propertyPath
     * @return void
     */
    public static function validRegularExpression($regularExpression, $propertyPath)
    {
        $pregMatchErrored = false;
        set_error_handler(
            function () use (&$pregMatchErrored) {
                $pregMatchErrored = true;
            }
        );

        preg_match($regularExpression, 'some test string');

        restore_error_handler();

        if ($pregMatchErrored || preg_last_error()) {
            throw static::createException(
                $regularExpression,
                sprintf('The pattern "%s" is not a valid regular expression', self::stringify($regularExpression)),
                static::INVALID_REGULAR_EXPRESSION,
                $propertyPath
            );
        }
    }
}
