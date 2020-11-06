<?php

namespace Tests\Unit\Boundary;

use Newsio\Boundary\IdBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

class IdBoundaryTest extends BaseTestCase
{
    public function test_Boundary_WithValidParameter_ReturnsInteger()
    {
        $boundary = new IdBoundary('3');
        $this->assertEquals(3, $boundary->getValue());
    }

    public function test_Boundary_WithAlphaNumParameter_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new IdBoundary('3svdsfvr');
    }

    public function test_Boundary_WithNullParameter_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new IdBoundary(null);
    }

    public function test_Boundary_WithArrayParameter_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new IdBoundary([]);
    }
}