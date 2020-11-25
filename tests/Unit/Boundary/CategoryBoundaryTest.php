<?php

namespace Tests\Unit\Boundary;

use Newsio\Boundary\CategoryBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

class CategoryBoundaryTest extends BaseTestCase
{
    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithValidCategory_ReturnsInteger()
    {
        $categoryBoundary = new CategoryBoundary(2);

        $this->assertEquals($categoryBoundary->getValue(), 2);
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithInvalidCategory_ReturnsInteger()
    {
        $this->expectException(BoundaryException::class);
        new CategoryBoundary([]);
    }
}