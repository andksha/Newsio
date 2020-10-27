<?php

namespace Newsio\Boundary;

use Newsio\Exception\BoundaryException;

class TitleBoundary
{
    private string $title;

    /**
     * LinkBoundary constructor.
     * @param $title
     * @throws BoundaryException
     *
     * @TODO: better title checking
     */
    public function __construct($title)
    {
        if (!is_string($title)) {
            throw new BoundaryException('Title is invalid');
        }

        $this->title = $title;
    }

    public function getValue(): string
    {
        return $this->title;
    }
}
