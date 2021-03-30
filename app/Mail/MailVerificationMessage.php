<?php

namespace App\Mail;

use App\UserEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailVerificationMessage extends Mailable {
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param UserEmail $userEmail
     */
    public function __construct(UserEmail $userEmail) {
        $this->userEmail = $userEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->view('mail.verify_email')->with([
                                                          'userEmail' => $this->userEmail
                                                      ])->subject("E-Mail verifizieren");
    }
}
