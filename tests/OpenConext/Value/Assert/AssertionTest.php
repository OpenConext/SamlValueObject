<?php

namespace OpenConext\Value\Assert;

use OpenConext\Value\Assert\Stub\CallMe;
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

    /**
     * @test
     *
     * @expectedException InvalidArgumentException
     */
    public function a_missing_key_makes_the_assertion_fail()
    {
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

    /**
     * @test
     *
     * @dataProvider \OpenConext\Value\TestDataProvider::notCallable
     * @expectedException InvalidArgumentException
     *
     * @param mixed $notCallable
     */
    public function invalid_callables_fail_the_is_callable_assertion($notCallable)
    {
        Assertion::isCallable($notCallable, 'callable');
    }

    /**
     * @test
     *
     * @dataProvider validCallableProvider
     *
     * @param Callable $callable
     */
    public function valid_callables_assertion_succeeds_if_value_is_a_callable($callable)
    {
        $exceptionCaught = false;

        try {
            Assertion::isCallable($callable, 'callable');
        } catch (InvalidArgumentException $exception) {
            $exceptionCaught = true;
        }

        $this->assertFalse($exceptionCaught, 'A valid callable should not cause an exception to be thrown');
    }

    public function validCallableProvider()
    {
        $closure = function () {};

        return array(
            'closure'              => array($closure),
            'instance callable'    => array(array(new CallMe(), 'instanceCallable')),
            'static callable'      => array(array('OpenConext\Value\Assert\Stub\CallMe', 'staticCallable')),
            'static class call'    => array('OpenConext\Value\Assert\Stub\CallMe::staticCallable'),
            'relative static call' => array(array('OpenConext\Value\Assert\Stub\CallMe', 'parent::relativeStaticCall')),
            'invokable'            => array(new CallMe())
        );
    }

    /**
     * Stub method
     */
    public function instanceCallable()
    {
    }

    /**
     * Stub method
     */
    public static function staticCallable()
    {
    }
}
