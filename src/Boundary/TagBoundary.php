<?php

namespace Newsio\Boundary;

use Newsio\Exception\BoundaryException;

class TagBoundary
{
    private string $tag;

    /**
     * LinkBoundary constructor.
     * @param $tag
     * @throws BoundaryException
     *
     * @TODO: better tag checking
     */
    public function __construct($tag)
    {
        if (!is_string($tag)) {
            throw new BoundaryException('Tag is invalid');
        }

        $this->tag = $tag;
    }

    public function getValue(): string
    {
        return $this->tag;
    }
}
