<?php

namespace App\Mail;

use App\Models\Game;
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
     * @param Collection<int, Game> $games
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
        $locale = $this->user->locale;

        return new Content(
            view: 'emails.maizzle.games.newGames',
            with: [
                'greetingName' => $this->user->first_name,
                'groups' => $this->groups($locale),
                'gameUrls' => $this->games
                    ->mapWithKeys(fn(Game $game) => [$game->id => FrontendUrl::game($game->uuid, $locale)])
                    ->all(),
                'gamesUrl' => FrontendUrl::games($locale),
            ],
        );
    }

    private function groups(?string $locale): Collection
    {
        [$grouped, $ungrouped] = $this->games
            ->sortBy('start_time')
            ->partition(fn(Game $game) => $game->gameGroup !== null);

        $buckets = $grouped
            ->sortBy(fn(Game $game) => $game->gameGroup->sort_order)
            ->groupBy(fn(Game $game) => $game->game_group_id)
            ->values();

        if ($ungrouped->isNotEmpty()) {
            $buckets->push($ungrouped);
        }

        return $buckets->map(function (Collection $games) use ($locale) {
            $group = $games->first()->gameGroup;

            return [
                'name' => $group?->name ?? __('games.mail.no_group'),
                'url' => $group ? FrontendUrl::gameGroup($group->uuid, $locale) : null,
                'games' => $games,
            ];
        });
    }
}
