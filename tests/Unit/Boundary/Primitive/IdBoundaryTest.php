<?php

namespace Tests\Unit\Boundary\Primitive;

use Newsio\Boundary\IdBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

class IdBoundaryTest extends BaseTestCase
{
    public function test_Boundary_WithValidParameter_ReturnsInteger()
    {
        $idBoundary = new IdBoundary('3');
        $this->assertEquals(3, $idBoundary->getValue());
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

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithArrayParameter_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new IdBoundary([]);
    }
}