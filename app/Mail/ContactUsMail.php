<?php

namespace App\Mail;

use App\Data\User\ContactUsData;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactUsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactUsData $data,
    )
    {
    }

    public function envelope()
    {
        return new Envelope(
            subject: "Kontaktinė forma",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contactUs',
        );
    }
}
