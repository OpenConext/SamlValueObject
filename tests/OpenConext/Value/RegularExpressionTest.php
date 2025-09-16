<?php

namespace OpenConext\Value;

use OpenConext\Value\Exception\InvalidArgumentException;


class RegularExpressionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notStringOrEmptyString')]
    #[\PHPUnit\Framework\Attributes\Group('value')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function a_regex_cannot_be_created_with_patterns_that_are_not_a_non_empty_string($pattern)
    {
        $this->expectException(\InvalidArgumentException::class);
        new RegularExpression($pattern);
    }

    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'invalidRegularExpressionProvider')]
    #[\PHPUnit\Framework\Attributes\Group('value')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function a_regex_can_be_tested_for_being_valid($invalidExpression)
    {
       $this->assertFalse(RegularExpression::isValidRegularExpression($invalidExpression));
    }

    #[\PHPUnit\Framework\Attributes\Group('value')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function a_valid_regular_expression_can_be_tested_for_being_valid()
    {
        $this->assertTrue(RegularExpression::isValidRegularExpression('/abc/i'));
    }

    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'invalidRegularExpressionProvider')]
    #[\PHPUnit\Framework\Attributes\Group('value')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function a_regex_cannot_be_created_with_an_invalid_regular_expression($invalidPattern)
    {
        $this->expectException(\InvalidArgumentException::class);
        new RegularExpression($invalidPattern);
    }

    /**
     *
     * @param mixed $invalidArgument
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'notString')]
    #[\PHPUnit\Framework\Attributes\Group('value')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function matches_requires_a_string_argument($invalidArgument)
    {
        $this->expectException(\InvalidArgumentException::class);
        $regularExpression = new RegularExpression('/abc/i');
        $regularExpression->matches($invalidArgument);
    }

    #[\PHPUnit\Framework\Attributes\Group('value')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function matches_tests_the_regular_expression_against_the_string()
    {
        $regularExpression = new RegularExpression('/abc/i');

        $this->assertTrue($regularExpression->matches('abc'));
        $this->assertTrue($regularExpression->matches('aBC'));
        $this->assertFalse($regularExpression->matches('bde'));
    }

    #[\PHPUnit\Framework\Attributes\Group('value')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function regexes_with_the_same_pattern_are_equal()
    {
        $base    = new RegularExpression('/abc/i');
        $theSame = new RegularExpression('/abc/i');

        $this->assertTrue($base->equals($theSame), 'Expected regular expressions to equal each other');
    }

    #[\PHPUnit\Framework\Attributes\Group('value')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function the_pattern_can_be_retrieved()
    {
        $pattern = '/a{3,4}/i';

        $regularExpression = new RegularExpression($pattern);

        $this->assertEquals($pattern, $regularExpression->getPattern());
    }

    #[\PHPUnit\Framework\Attributes\Group('value')]
    #[\PHPUnit\Framework\Attributes\Test]
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

    #[\PHPUnit\Framework\Attributes\Group('value')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserializing_a_serialized_regular_expression_results_in_an_equal_value_object()
    {
        $regularExpression = '/abc/i';

        $original     = new RegularExpression($regularExpression);
        $deserialized = RegularExpression::deserialize($original->serialize());

        $this->assertTrue($original->equals($deserialized));
        $this->assertEquals($regularExpression, $deserialized->getPattern());
    }

    /**
     *
     *
     * @param string $invalidPattern
     */
    #[\PHPUnit\Framework\Attributes\DataProviderExternal(\OpenConext\Value\TestDataProvider::class, 'invalidRegularExpressionProvider')]
    #[\PHPUnit\Framework\Attributes\Group('metadata')]
    #[\PHPUnit\Framework\Attributes\Group('contactperson')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function deserialization_requires_valid_data($invalidPattern)
    {
        $this->expectException(\InvalidArgumentException::class);
        RegularExpression::deserialize($invalidPattern);
    }

    #[\PHPUnit\Framework\Attributes\Group('value')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function a_regular_expression_can_be_cast_to_string()
    {
        $pattern = '/abc/i';
        $regularExpression = new RegularExpression($pattern);

        $string = (string) $regularExpression;

        $this->assertEquals($pattern, $string);
    }
}
