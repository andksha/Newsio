<?php

namespace Tests\Unit\Boundary\Primitive;

use Newsio\Boundary\NullableIntBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

class NullableIntBoundaryTest extends BaseTestCase
{
    public function test_Boundary_WithValidParameter_ReturnsInt()
    {
        $nullableIntBoundary = new NullableIntBoundary('3');
        $this->assertEquals(3, $nullableIntBoundary->getValue());
    }

    public function test_Boundary_WithNullParameter_ReturnsNull()
    {
        $nullableIntBoundary = new NullableIntBoundary(null);
        $this->assertEquals(null, $nullableIntBoundary->getValue());
    }

    public function test_Boundary_WithAlphaNumParameter_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new NullableIntBoundary('3svdsfvr');
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithArrayParameter_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new NullableIntBoundary([]);
    }
}