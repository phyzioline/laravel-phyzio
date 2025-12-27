<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class CompanyAccountRejectedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $adminNote;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $adminNote = null)
    {
        $this->user = $user;
        $this->adminNote = $adminNote;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('phyzioline@gmail.com', 'Phyzioline'),
            subject: __('Company Account Review - Phyzioline'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.company-account-rejected',
            with: [
                'user' => $this->user,
                'adminNote' => $this->adminNote,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}

