<?php

namespace Tests\Unit\Boundary\Primitive;

use Newsio\Boundary\StringBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

class StringBoundaryTest extends BaseTestCase
{
    public function test_Boundary_WithValidParameter_ReturnsString()
    {
        $boundary = new StringBoundary('string');
        $this->assertEquals('string', $boundary->getValue());
    }

    public function test_Boundary_WithEmptyParameter_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new StringBoundary('');
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithArrayParameter_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new StringBoundary(['test', 'test2']);
    }
}