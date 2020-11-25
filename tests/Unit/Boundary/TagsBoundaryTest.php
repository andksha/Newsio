<?php

namespace Tests\Unit\Boundary;

use Newsio\Boundary\TagsBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

class TagsBoundaryTest extends BaseTestCase
{
    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithValidTags_ReturnsUniqueTags()
    {
        $tagBoundary = new TagsBoundary(['tag1', 'tag2', 'tag2']);

        $this->assertEquals(['tag1', 'tag2'], $tagBoundary->getValues());
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithEmptyArray_ReturnsEmptyArray()
    {
        $tagBoundary = new TagsBoundary([]);

        $this->assertEquals([], $tagBoundary->getValues());
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithNullTag_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new TagsBoundary(null);
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithInvalidTags_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new TagsBoundary([null, 34, 'test']);
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithEmptyTags_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new TagsBoundary(['']);
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithInvalidFormat_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new TagsBoundary(['tag-(1^)']);
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithTooManyTags_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new TagsBoundary(['tag1', 'tag2', 'tag3', 'tag4', 'tag5', 'tag6']);
    }
}