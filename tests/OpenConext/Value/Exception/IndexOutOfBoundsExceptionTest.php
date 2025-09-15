<?php

namespace OpenConext\Value\Exception;



class IndexOutOfBoundsExceptionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('exception')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function too_low_creates_an_exception_with_a_known_format_message()
    {
        $invalidIndex = -1;
        $minimumIndex = 0;

        $exception = IndexOutOfBoundsException::tooLow($invalidIndex, $minimumIndex);

        $this->assertInstanceOf('\OpenConext\Value\Exception\IndexOutOfBoundsException', $exception);
        $this->assertSame(
            sprintf('Index "%d" is lower than the minimum index "%d"', $invalidIndex, $minimumIndex),
            $exception->getMessage()
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('exception')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function a_too_low_index_has_the_invalid_index_and_minimum_index_not_maximum_index()
    {
        $invalidIndex = -1;
        $minimumIndex = 0;

        $exception = IndexOutOfBoundsException::tooLow($invalidIndex, $minimumIndex);

        $this->assertEquals($invalidIndex, $exception->getInvalidIndex());
        $this->assertEquals($minimumIndex, $exception->getMinimumIndex());
        $this->assertNull($exception->getMaximumIndex());
    }

    #[\PHPUnit\Framework\Attributes\Group('exception')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function too_high_creates_an_exception_with_a_known_format_message()
    {
        $invalidIndex = 5;
        $maximumIndex = 4;

        $exception = IndexOutOfBoundsException::tooHigh($invalidIndex, $maximumIndex);

        $this->assertInstanceOf('\OpenConext\Value\Exception\IndexOutOfBoundsException', $exception);
        $this->assertSame(
            sprintf('Index "%d" is higher than the maximum index "%d"', $invalidIndex, $maximumIndex),
            $exception->getMessage()
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('exception')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function a_too_high_index_has_the_invalid_index_and_maximum_index_not_minimum_index()
    {
        $invalidIndex = 5;
        $maximumIndex = 4;

        $exception = IndexOutOfBoundsException::tooHigh($invalidIndex, $maximumIndex);

        $this->assertEquals($invalidIndex, $exception->getInvalidIndex());
        $this->assertEquals($maximumIndex, $exception->getMaximumIndex());
        $this->assertNull($exception->getMinimumIndex());
    }
}
