<?php

namespace App\Jobs;

use App\Mail\NewGamesAnnouncementMail;
use App\Models\Game;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class NotifyUsersAboutNewGames implements ShouldQueue
{
    use Queueable;

    /**
     * @param array<int> $gameIds
     */
    public function __construct(
        public array $gameIds,
    )
    {
    }

    public function handle(): void
    {
        $games = Game::with(['courtType', 'court'])->whereIn('id', $this->gameIds)->get();

        if ($games->isEmpty()) {
            return;
        }

        User::where('notify_about_games', true)
            ->chunkById(200, function ($users) use ($games) {
                foreach ($users as $user) {
                    Mail::queue(new NewGamesAnnouncementMail($user, $games));
                }
            });
    }
}
