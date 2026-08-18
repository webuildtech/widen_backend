<?php

namespace App\Mail;

use App\Models\Game;
use App\Models\GameParticipant;
use App\Support\FrontendUrl;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GameCanceledMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Game            $game,
        public GameParticipant $participant,
        public float           $refundedAmount,
        public ?string         $reason = null,
    )
    {
        $this->locale($participant->user?->locale ?? FrontendUrl::DEFAULT_LOCALE->value);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->participant->email,
            subject: __('games.mail.canceled_subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.maizzle.games.canceled',
            with: [
                'game' => $this->game,
                'greetingName' => $this->participant->first_name,
                'refundedAmount' => $this->refundedAmount,
                'reason' => $this->reason,
            ],
        );
    }
}
