<?php

namespace OpenConext\Value;

use OpenConext\Value\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase as UnitTest;

class RegularExpressionTest extends UnitTest
{
    /**
     * @test
     * @group value
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notStringOrEmptyString
     *
     * @expectedException InvalidArgumentException
     */
    public function a_regex_cannot_be_created_with_patterns_that_are_not_a_non_empty_string($pattern)
    {
        new RegularExpression($pattern);
    }

    /**
     * @test
     * @group value
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::invalidRegularExpressionProvider
     */
    public function a_regex_can_be_tested_for_being_valid($invalidExpression)
    {
       $this->assertFalse(RegularExpression::isValidRegularExpression($invalidExpression));
    }

    /**
     * @test
     * @group value
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::invalidRegularExpressionProvider
     *
     * @expectedException InvalidArgumentException
     */
    public function a_regex_cannot_be_created_with_an_invalid_regular_expression($invalidPattern)
    {
        new RegularExpression($invalidPattern);
    }

    /**
     * @test
     * @group value
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notString
     * @expectedException InvalidArgumentException
     *
     * @param mixed $invalidArgument
     */
    public function matches_requires_a_string_argument($invalidArgument)
    {
        $regularExpression = new RegularExpression('/abc/i');
        $regularExpression->matches($invalidArgument);
    }

    /**
     * @test
     * @group value
     */
    public function matches_tests_the_regular_expression_against_the_string()
    {
        $regularExpression = new RegularExpression('/abc/i');

        $this->assertTrue($regularExpression->matches('abc'));
        $this->assertTrue($regularExpression->matches('aBC'));
        $this->assertFalse($regularExpression->matches('bde'));
    }

    /**
     * @test
     * @group value
     */
    public function regexes_with_the_same_pattern_are_equal()
    {
        $base    = new RegularExpression('/abc/i');
        $theSame = new RegularExpression('/abc/i');

        $this->assertTrue($base->equals($theSame), 'Expected regular expressions to equal each other');
    }

    /**
     * @test
     * @group value
     */
    public function regexes_with_a_different_pattern_are_not_equal()
    {
        $base                 = new RegularExpression('/abc/');
        $differentByPattern   = new RegularExpression('/33/');
        $differentByDelimiter = new RegularExpression('~abc~');

        $this->assertFalse(
            $base->equals($differentByPattern),
            'Regular expressions with different patterns must not be equal'
        );

        $this->assertFalse(
            $base->equals($differentByDelimiter),
            'Regular expressions with different delimiters must not be equal'
        );
    }

    /**
     * @test
     * @group value
     */
    public function a_regular_expression_can_be_cast_to_string()
    {
        $pattern = '/abc/i';
        $regularExpression = new RegularExpression($pattern);

        $string = (string) $regularExpression;

        $this->assertEquals($pattern, $string);
    }
}
