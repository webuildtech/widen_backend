<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Support\FrontendUrl;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionRenewalLinkMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Subscription $subscription,
        public float        $price,
        public string       $renewalUrl,
    )
    {
        $this->locale($subscription->subscriber->locale ?? FrontendUrl::DEFAULT_LOCALE->value);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->subscription->subscriber->email,
            subject: __('subscriptions.mail.renewal_link_subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.maizzle.subscriptionRenewalLink',
        );
    }
}
