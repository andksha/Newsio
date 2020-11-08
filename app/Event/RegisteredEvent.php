<?php

namespace App\Event;

use Illuminate\Foundation\Events\Dispatchable;

class RegisteredEvent
{
    use Dispatchable;

    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }
}