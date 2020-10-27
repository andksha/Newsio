<?php

namespace Newsio\Boundary;

use Illuminate\Support\Facades\Validator;
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
            || !array_sum(array_map('is_string', $links)) == count($links)
        ) {
            throw new BoundaryException('Link is invalid');
        }

        $this->links = $links;
    }

    public function getValues(): array
    {
        return $this->links;
    }
}
