<?php

namespace App\Mail\Auth;

use App\Model\EmailConfirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    private EmailConfirmation $emailConfirmation;

    public function __construct(EmailConfirmation $emailConfirmation)
    {
        $this->subject = __('email_subjects.forgot_password');
        $this->emailConfirmation = $emailConfirmation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.auth.registered')
            ->with([
                'emailConfirmation' => $this->emailConfirmation,
            ])->subject($this->subject);
    }
}