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
        if (
            !is_numeric($value)
            || !is_int((int)$value)
            || is_float($value)
        ) {
            throw new BoundaryException('Invalid id');
        }

        $this->value = (int)$value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
