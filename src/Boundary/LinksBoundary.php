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
        if (
            !is_array($links)
            || array_search('', $links, true) !== false
            || array_search(false, array_map('is_string', $links), true) !== false
        ) {
            throw new BoundaryException('Links must be separated by single space', ['links' => 'Links are invalid']);
        }

        $this->links = array_unique($links);
    }

    public function getValues(): array
    {
        return $this->links;
    }
}
