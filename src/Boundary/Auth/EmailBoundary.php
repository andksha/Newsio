<?php

namespace Newsio\Boundary\Auth;

use App\Model\User;
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
        if (!is_string($email) || !preg_match(User::EMAIL_REGEX, $email)) {
            throw new BoundaryException('Invalid email');
        }

        $this->email = $email;
    }

    public function getValue(): string
    {
        return $this->email;
    }
}