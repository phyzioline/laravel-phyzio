<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedbackMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->view('web.mail.feedback')
                    ->subject('New Feedback: ' . $this->data['subject'])
                    ->from($this->data['email'], $this->data['first_name'] . ' ' . $this->data['last_name']);
    }
}
