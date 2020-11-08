<?php

namespace Newsio\Boundary;

use Newsio\Exception\BoundaryException;

class TagsBoundary
{
    private array $tags;
    public const TAG_REGEX = '/^[-a-zA-Z0-9_%#]{0,15}$/';

    /**
     * LinkBoundary constructor.
     * @param $tags
     * @throws BoundaryException
     *
     */
    public function __construct($tags)
    {
        if (!is_array($tags)) {
            throw new BoundaryException('Invalid tags format', ['tags' => 'Tags are invalid']);
        }

        if (count($tags) > 5) {
            throw new BoundaryException('Only 5 tags are allowed', ['tags' => 'Only 5 tags are allowed']);
        }

        foreach ($tags as $key => $value) {
            if (!is_string($value) || $value === '' || !preg_match(self::TAG_REGEX, $value)) {
                throw new BoundaryException('Invalid tags format', ['tags' => 'Tags are invalid']);
            }
        }

        $this->tags = array_unique($tags);
    }

    public function getValues(): array
    {
        return $this->tags;
    }
}
