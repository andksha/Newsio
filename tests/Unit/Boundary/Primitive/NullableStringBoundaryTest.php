<?php

namespace Tests\Unit\Boundary\Primitive;

use Newsio\Boundary\NullableStringBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

class NullableStringBoundaryTest extends BaseTestCase
{
    public function test_Boundary_WithStringParameter_ReturnsStringValue()
    {
        $boundary = new NullableStringBoundary('test');

        $this->assertEquals($boundary->getValue(), 'test');
    }

    public function test_Boundary_WithNullParameter_ReturnsNull()
    {
        $boundary = new NullableStringBoundary(null);

        $this->assertEquals($boundary->getValue(), null);
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithArrayParameter_ThrowsException()
    {
        $this->expectException(BoundaryException::class);
        new NullableStringBoundary([]);
    }

    public function test_Boundary_WithEmptyStringParameter_ThrowsException()
    {
        $this->expectException(BoundaryException::class);
        new NullableStringBoundary('');
    }
}