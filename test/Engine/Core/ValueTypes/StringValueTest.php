<?php

namespace Test\Engine\Core\ValueTypes;

use Engine\Core\ValueTypes\StringValue;
use Engine\Exceptions\ValueException;
use PHPUnit\Framework\TestCase;

class StringValueTest extends TestCase
{
    function testHasLengthWithCorrectLengthDoesNotThrow() : void
    {
        $val = new StringValue('abc');
        $this->assertSame($val, $val->has_length(3));
        // no exception
    }

    function testHasLengthWithIncorrectLengthThrows() : void
    {
        $this->expectException(ValueException::class);
        $val = new StringValue('abc');
        $this->assertSame($val, $val->has_length(4));
        // exception
    }
}
