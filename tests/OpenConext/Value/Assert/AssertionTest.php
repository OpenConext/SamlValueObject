<?php

namespace OpenConext\Value\Assert;

use OpenConext\Value\Assert\Stub\CallMe;
use OpenConext\Value\Exception\InvalidArgumentException;


class AssertionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
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
     *
     * @param mixed $value
     */
    public function not_strings_or_empty_strings_are_invalid($value)
    {
        $this->expectException(\InvalidArgumentException::class);
        Assertion::nonEmptyString($value, 'value');
    }

    /**
     * @test
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::invalidRegularExpressionProvider
     *
     * @param string $invalidPattern
     */
    public function an_invalid_regular_expression_does_not_pass_the_assertion($invalidPattern)
    {
        $this->expectException(\InvalidArgumentException::class);
        Assertion::validRegularExpression($invalidPattern, 'invalidPattern');
    }

    /**
     * @test
     */
    public function a_missing_key_makes_the_assertion_fail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $requiredKeys = array('a', 'b');
        $actualData   = array('a' => 1, 'c' => 2);

        Assertion::keysExist($actualData, $requiredKeys);
    }

    /**
     * @test
     */
    public function keys_exists_assertion_succeeds_if_all_required_keys_are_present_()
    {
        $requiredKeys = array('a', 'b', 'c');
        $match        = array('c' => 1, 'a' => 2, 'b' => 'foo');
        $superfluous  = array('d' => 1, 'a' => 2, 'c' => 3, 'b' => 4);

        $exceptionCaught = false;
        try {
            Assertion::keysExist($match, $requiredKeys);
            Assertion::keysExist($superfluous, $requiredKeys);
        } catch (InvalidArgumentException $exception) {
            $exceptionCaught = true;
        }

        $this->assertFalse($exceptionCaught, 'When all required keys are present, no exception should be thrown');
    }
}
