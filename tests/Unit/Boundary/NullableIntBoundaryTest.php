<?php

namespace Tests\Unit\Boundary;

use Newsio\Boundary\NullableIntBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

class NullableIntBoundaryTest extends BaseTestCase
{
    public function test_Boundary_WithValidParameter_ReturnsInt()
    {
        $boundary = new NullableIntBoundary('3');
        $this->assertEquals(3, $boundary->getValue());
    }

    public function test_Boundary_WithNullParameter_ReturnsNull()
    {
        $boundary = new NullableIntBoundary(null);
        $this->assertEquals(null, $boundary->getValue());
    }

    public function test_Boundary_WithAlphaNumParameter_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new NullableIntBoundary('3svdsfvr');
    }

    public function test_Boundary_WithArrayParameter_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new NullableIntBoundary([]);
    }
}