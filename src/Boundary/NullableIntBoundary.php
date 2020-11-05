<?php

namespace Newsio\Boundary;

use Newsio\Exception\BoundaryException;

class NullableIntBoundary
{
    private ?int $value;

    /**
     * NullableStringBoundary constructor.
     * @param $value
     * @param string $message
     * @throws BoundaryException
     */
    public function __construct($value, string $message = 'Value must be empty or integer')
    {
        if ($value === null || is_numeric($value) && (int)$value !== 0) {
            $this->value = $value;
        } else {
            throw new BoundaryException($message, []);
        }
    }

    public function getValue(): ?int
    {
        return $this->value;
    }
}