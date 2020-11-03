<?php

namespace Tests\Unit\Boundary;

use Newsio\Boundary\TagsBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

class TagsBoundaryTest extends BaseTestCase
{
    public function test_Boundary_WithValidTags_ReturnsUniqueTags()
    {
        $boundary = new TagsBoundary(['tag1', 'tag2', 'tag2']);

        $this->assertEquals(['tag1', 'tag2'], $boundary->getValues());
    }

    public function test_Boundary_WithEmptyArray_ReturnsEmptyArray()
    {
        $boundary = new TagsBoundary([]);

        $this->assertEquals([], $boundary->getValues());
    }

    public function test_Boundary_WithNullTag_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new TagsBoundary(null);
    }

    public function test_Boundary_WithInvalidTags_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new TagsBoundary([null, 34, 'test']);
    }

    public function test_Boundary_WithEmptyTags_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new TagsBoundary(['']);
    }

    public function test_Boundary_WithInvalidFormat_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new TagsBoundary(['tag-(1^)']);
    }
}