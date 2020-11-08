<?php

namespace Newsio\Boundary\Auth;

use App\Model\User;
use Newsio\Exception\BoundaryException;

class PasswordBoundary
{
    private string $password;

    /**
     * RegisterBoundary constructor.
     * @param $password
     * @throws BoundaryException
     */
    public function __construct($password)
    {
        if (!is_string($password) || !preg_match(User::PASSWORD_REGEX, $password)) {
            throw new BoundaryException('Invalid password');
        }

        $this->password = $password;
    }

    public function getValue()
    {
        return $this->password;
    }
}