<?php

namespace Newsio\Boundary\Auth;

use Newsio\Exception\BoundaryException;

class EmailBoundary
{
    private string $email;

    /**
     * RegisterBoundary constructor.
     * @param $email
     * @throws BoundaryException
     */
    public function __construct($email)
    {
        if (!is_string($email) || filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new BoundaryException('Invalid email');
        }

        $this->email = $email;
    }

    public function getValue(): string
    {
        return $this->email;
    }
}