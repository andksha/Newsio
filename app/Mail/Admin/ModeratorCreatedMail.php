<?php

namespace App\Mail\Admin;

use App\Model\ModeratorConfirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ModeratorCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    private ModeratorConfirmation $moderatorConfirmation;

    public function __construct(ModeratorConfirmation $moderatorConfirmation)
    {
        $this->subject = 'Registration';
        $this->moderatorConfirmation = $moderatorConfirmation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin.moderator')
            ->with([
                'moderatorConfirmation' => $this->moderatorConfirmation,
            ])->subject($this->subject);
    }
}