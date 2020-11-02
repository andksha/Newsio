<?php

namespace Tests\Unit\Boundary;

use Newsio\Boundary\LinksBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

class LinksBoundaryTest extends BaseTestCase
{
    public function test_Boundary_WithValidTags_ReturnsUniqueTags()
    {
        $boundary = new LinksBoundary(['link1', 'link2', 'link2']);

        $this->assertEquals(['link1', 'link2'], $boundary->getValues());
    }

    public function test_Boundary_WithEmptyArray_ReturnsEmptyArray()
    {
        $boundary = new LinksBoundary([]);

        $this->assertEquals([], $boundary->getValues());
    }

    public function test_Boundary_WithInvalidFormat_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        $boundary = new LinksBoundary(null);
    }

    public function test_Boundary_WithInvalidTags_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        $boundary = new LinksBoundary([null, 34, 'test']);
    }

    public function test_Boundary_WithEmptyTags_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        $boundary = new LinksBoundary(['']);
    }
}