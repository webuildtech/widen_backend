<?php

namespace App\Mail;

use App\Models\User;
use App\Support\FrontendUrl;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class NewGamesAnnouncementMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @param Collection<int, \App\Models\Game> $games
     */
    public function __construct(
        public User       $user,
        public Collection $games,
    )
    {
        $this->locale($user->locale ?? FrontendUrl::DEFAULT_LOCALE->value);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->user->email,
            subject: __('games.mail.new_games_subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.maizzle.games.newGames',
            with: [
                'games' => $this->games,
                'greetingName' => $this->user->first_name,
                'gameUrls' => $this->games
                    ->mapWithKeys(fn($game) => [$game->id => FrontendUrl::game($game->uuid, $this->user->locale)])
                    ->all(),
            ],
        );
    }
}
