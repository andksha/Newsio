<?php

namespace Tests\Unit\Boundary;

use Newsio\Boundary\CategoryBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

class CategoryBoundaryTest extends BaseTestCase
{
    public function test_Boundary_WithValidCategory_ReturnsInteger()
    {
        $boundary = new CategoryBoundary(2);

        $this->assertEquals($boundary->getValue(), 2);
    }

    public function test_Boundary_WithInvalidCategory_ReturnsInteger()
    {
        $this->expectException(BoundaryException::class);

        $boundary = new CategoryBoundary([]);
    }
}