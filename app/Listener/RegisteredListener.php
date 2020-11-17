<?php

namespace App\Listener;

use App\Event\RegisteredEvent;
use App\Mail\Auth\RegisteredMail;
use App\Model\EmailConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisteredListener
{
    public function handle(RegisteredEvent $registered)
    {
        $emailConfirmation = EmailConfirmation::query()->create([
            'email' => $registered->getEmail(),
            'token' => Str::random(32)
        ]);

        Mail::to($registered->getEmail())->queue(new RegisteredMail($emailConfirmation));
    }
}