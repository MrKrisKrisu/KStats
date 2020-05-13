<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyMailSettings extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, string $email, string $verify_key)
    {
        $this->user = $user;
        $this->email = $email;
        $this->verify_key = $verify_key;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.verify_email')
            ->with([
                'username' => $this->user->username,
                'email' => $this->email,
                'verify_key' => $this->verify_key
            ]);
    }
}
