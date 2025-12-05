<?php

namespace App\Mail;

use App\Data\User\Forms\CompanyFormStoreData;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompanyFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CompanyFormStoreData $data,
    )
    {
    }

    public function envelope()
    {
        return new Envelope(
            subject: "Įmonių forma",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.companyForm',
        );
    }
}
