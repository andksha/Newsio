<?php

namespace App\Mail\Auth;

use App\Model\PasswordReset;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    private PasswordReset $passwordReset;

    public function __construct(PasswordReset $passwordReset)
    {
        $this->subject = 'Resetting password';
        $this->passwordReset = $passwordReset;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.auth.reset_password')
            ->with([
                'passwordReset' => $this->passwordReset,
            ])->subject($this->subject);
    }
}