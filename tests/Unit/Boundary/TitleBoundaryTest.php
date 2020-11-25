<?php

namespace Tests\Unit\Boundary;

use Newsio\Boundary\TitleBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

class TitleBoundaryTest extends BaseTestCase
{
    public function test_Boundary_WithValidTitle_ReturnsStringValue()
    {
        $titleBoundary = new TitleBoundary('title');

        $this->assertEquals($titleBoundary->getValue(), 'title');
    }

    public function test_Boundary_WithEmptyTitle_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new TitleBoundary('');
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithArrayTitle_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new TitleBoundary([]);
    }

    public function test_Boundary_WithNullTitle_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new TitleBoundary(null);
    }

    public function test_Boundary_WithInvalidFormat_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new TitleBoundary('title(test)');
    }
}