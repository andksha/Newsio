<?php

namespace Newsio\Boundary;

use Newsio\Exception\BoundaryException;

class LinksBoundary
{
    private array $links;
    private int $length;
    public static string $LINK_REGEX = '/^https:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&\/=]*)$/';

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
            throw new BoundaryException('Invalid link format', ['links' => 'Links are invalid']);
        }

        foreach ($links as $key => $value) {
            if (!is_string($value) || $value === '' || !preg_match(self::$LINK_REGEX, $value)) {
                throw new BoundaryException('Invalid link format', ['links' => 'Links are invalid']);
            }
        }

        $this->links = array_unique($links);
        $this->length = count($this->links);
    }

    public function getValues(): array
    {
        return $this->links;
    }

    public function getLength(): int
    {
        return $this->length;
    }
}
