<?php

namespace Newsio\Boundary;

use Newsio\Exception\BoundaryException;

class TagsBoundary
{
    private array $tags;

    /**
     * LinkBoundary constructor.
     * @param $tags
     * @throws BoundaryException
     *
     * @TODO: better tags checking
     */
    public function __construct($tags)
    {
        if (!is_array($tags) || !array_sum(array_map('is_string', $tags)) == count($tags)) {
            throw new BoundaryException('Invalid tags format', ['tags' => 'Tags are invalid']);
        }

        $this->tags = array_unique($tags);
    }

    public function getValues(): array
    {
        return $this->tags;
    }
}
