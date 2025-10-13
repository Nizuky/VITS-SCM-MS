<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentVerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $url;
    public $user;

    public function __construct($url, $user)
    {
        $this->url = $url;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Verify your PLV email address')
            ->view('emails.student-verify')
            ->with(['url' => $this->url, 'user' => $this->user]);
    }
}
