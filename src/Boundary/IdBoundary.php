<?php

namespace Newsio\Boundary;

use Newsio\Exception\BoundaryException;

class IdBoundary
{
    private int $value;

    /**
     * IdBoundary constructor.
     * @param $value
     * @throws BoundaryException
     */
    public function __construct($value)
    {
        if (!is_int($value)) {
            throw new BoundaryException('Invalid id');
        }

        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
