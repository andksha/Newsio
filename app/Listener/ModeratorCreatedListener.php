<?php

namespace App\Listener;

use App\Event\ModeratorCreatedEvent;
use App\Mail\Admin\ModeratorCreatedMail;
use App\Model\ModeratorConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ModeratorCreatedListener
{
    public function handle(ModeratorCreatedEvent $event)
    {
        $moderatorConfirmation = ModeratorConfirmation::query()->create([
            'email' => $event->getEmail(),
            'token' => Str::random(32)
        ]);

        Mail::to($moderatorConfirmation->email)->queue(new ModeratorCreatedMail($moderatorConfirmation));
    }
}