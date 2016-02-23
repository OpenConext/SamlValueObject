<?php

namespace OpenConext\Value\Assert;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;

class AssertionTest extends UnitTest
{
    /**
     * @test
     */
    public function non_empty_strings_are_valid()
    {
        Assertion::nonEmptyString('0', 'test');
        Assertion::nonEmptyString('text', 'test');
        Assertion::nonEmptyString("new\nlines\nincluded", 'test');
    }

    /**
     * @test
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString()
     * @expectedException InvalidArgumentException
     *
     * @param mixed $value
     */
    public function not_strings_or_empty_strings_are_invalid($value)
    {
        Assertion::nonEmptyString($value, 'value');
    }

    /**
     * @test
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::invalidRegularExpressionProvider
     * @expectedException InvalidArgumentException
     *
     * @param string $invalidPattern
     */
    public function an_invalid_regular_expression_does_not_pass_the_assertion($invalidPattern)
    {
        Assertion::validRegularExpression($invalidPattern, 'invalidPattern');
    }
}
