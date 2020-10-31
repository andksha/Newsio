<?php

namespace Newsio\Boundary;

use Newsio\Exception\BoundaryException;

class LinksBoundary
{
    private array $links;

    /**
     * LinkBoundary constructor.
     * @param $links
     * @throws BoundaryException
     *
     * @TODO: better link format checking
     */
    public function __construct($links)
    {
        if (!is_array($links)) {
            throw new BoundaryException('Links must be separated by single space', ['links' => 'Links are invalid']);
        }

        foreach ($links as $key => $value) {
            if (!is_string($value) || $value === '') {
                throw new BoundaryException('Links must be separated by single space', ['links' => 'Links are invalid']);
            }
        }

        $this->links = array_unique($links);
    }

    public function getValues(): array
    {
        return $this->links;
    }
}
