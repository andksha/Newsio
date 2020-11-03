<?php

namespace Tests\Unit\Boundary;

use Newsio\Boundary\LinksBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

class LinksBoundaryTest extends BaseTestCase
{
    public function test_Boundary_WithValidTags_ReturnsUniqueTags()
    {
        $boundary = new LinksBoundary(['https://test.com', 'https://test2.com', 'https://test2.com']);

        $this->assertEquals(['https://test.com', 'https://test2.com'], $boundary->getValues());
    }

    public function test_Boundary_WithEmptyArray_ReturnsEmptyArray()
    {
        $boundary = new LinksBoundary([]);

        $this->assertEquals([], $boundary->getValues());
    }

    public function test_Boundary_WithNullLinks_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new LinksBoundary(null);
    }

    public function test_Boundary_WithInvalidLinks_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new LinksBoundary([null, 34, 'test']);
    }

    public function test_Boundary_WithEmptyLinks_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new LinksBoundary(['']);
    }

    public function test_Boundary_WithInvalidFormat_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new LinksBoundary(['faewirfoef']);
    }
}