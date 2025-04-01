<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PlanSubscribeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Payment $payment,
        public bool $renew = false
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->payment->user->email,
            subject: 'Planas suaktyvintas',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.maizzle.payments.planSubscribe',
        );
    }

    public function attachments(): array
    {
        return [Storage::disk('local')->path($this->payment->invoice_path)];
    }
}
