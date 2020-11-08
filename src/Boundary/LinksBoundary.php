<?php

namespace Newsio\Boundary;

use Newsio\Exception\BoundaryException;

class LinksBoundary
{
    private array $links;
    public const LINK_REGEX = '/^https:\/\/(www\.)?[-a-zA-Z0-9]{1,256}\.[a-zA-Z0-9]{1,6}\.?[a-zA-Z0-9]{0,6}\/[-a-zA-Z0-9()@:%_+.~#?&\/=]+$/';

    /**
     * LinkBoundary constructor.
     * @param $links
     * @throws BoundaryException
     *
     */
    public function __construct($links)
    {
        if (!is_array($links)) {
            throw new BoundaryException('Invalid link format', ['links' => 'Links are invalid']);
        }

        if (count($links) > 3) {
            throw new BoundaryException('Only 3 links are allowed', ['tags' => 'Only 3 links are allowed']);
        }

        foreach ($links as $key => $value) {
            if (!is_string($value) || $value === '' || !preg_match(self::LINK_REGEX, $value)) {
                throw new BoundaryException('Invalid link format', ['links' => 'Links are invalid']);
            }
        }

        $this->links = array_unique($links);
    }

    public function getValues(): array
    {
        return $this->links;
    }
}
