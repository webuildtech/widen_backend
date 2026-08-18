<?php

namespace App\Mail;

use App\Models\GameParticipant;
use App\Support\FrontendUrl;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GameJoinedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public GameParticipant $participant,
    )
    {
        $this->locale($participant->user?->locale ?? FrontendUrl::DEFAULT_LOCALE->value);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->participant->email,
            subject: __('games.mail.joined_subject'),
        );
    }

    public function content(): Content
    {
        $game = $this->participant->game;

        return new Content(
            view: 'emails.maizzle.games.joined',
            with: [
                'game' => $game,
                'greetingName' => $this->participant->first_name,
                'addedByName' => $this->participant->addedBy?->full_name,
                'gameUrl' => FrontendUrl::game($game->uuid, $this->participant->user?->locale),
            ],
        );
    }
}
