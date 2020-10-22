<?php

namespace Newsio\Boundary;

use Newsio\Exception\BoundaryException;

class LinkBoundary
{
    private string $link;

    /**
     * LinkBoundary constructor.
     * @param $link
     * @throws BoundaryException
     *
     * @TODO: better link checking
     */
    public function __construct($link)
    {
        if (!is_string($link)) {
            throw new BoundaryException('Link is invalid');
        }

        $this->link = $link;
    }

    public function getValue(): string
    {
        return $this->link;
    }
}
