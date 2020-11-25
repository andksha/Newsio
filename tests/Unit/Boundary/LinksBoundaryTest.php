<?php

namespace Tests\Unit\Boundary;

use Newsio\Boundary\LinksBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

class LinksBoundaryTest extends BaseTestCase
{
    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithValidTags_ReturnsUniqueTags()
    {
        $linksBoundary = new LinksBoundary(['https://test.com/test', 'https://test2.com/test', 'https://test2.com/test']);

        $this->assertEquals(['https://test.com/test', 'https://test2.com/test'], $linksBoundary->getValues());
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithEmptyArray_ReturnsEmptyArray()
    {
        $linksBoundary = new LinksBoundary([]);

        $this->assertEquals([], $linksBoundary->getValues());
    }

    public function test_Boundary_WithNullLinks_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new LinksBoundary(null);
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithInvalidLinks_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new LinksBoundary([null, 34, 'test']);
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithEmptyLinks_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new LinksBoundary(['']);
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithInvalidFormat_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new LinksBoundary(['faewirfoef']);
    }
}