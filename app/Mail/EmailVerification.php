<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationCode;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $verificationCode)
    {
        $this->user = $user;
        $this->verificationCode = $verificationCode;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Email Verification Code - ' . config('app.name'))
                    ->view('email.verification')
                    ->with([
                        'user' => $this->user,
                        'verificationCode' => $this->verificationCode,
                        'appName' => config('app.name')
                    ]);
    }
}

