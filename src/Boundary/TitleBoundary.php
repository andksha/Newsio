<?php

namespace Newsio\Boundary;

use Newsio\Exception\BoundaryException;

class TitleBoundary
{
    private string $title;
    public const TITLE_REGEX = '/^[-a-zA-Z0-9\s\:]{1,256}$/';

    /**
     * LinkBoundary constructor.
     * @param $title
     * @throws BoundaryException
     *
     */
    public function __construct($title)
    {
        if (!is_string($title) || $title === '' || !preg_match(self::TITLE_REGEX, $title)) {
            throw new BoundaryException('Title is invalid', ['title' => 'Title is invalid']);
        }

        $this->title = $title;
    }

    public function getValue(): string
    {
        return $this->title;
    }
}
