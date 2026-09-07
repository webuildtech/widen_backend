<?php

namespace App\Console\Commands;

use App\Jobs\NotifyUsersAboutNewGames;
use App\Models\Game;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Log;

class AnnounceNewGamesCommand extends Command
{
    protected $signature = 'app:announce-new-games-command';

    protected $description = 'Sends one digest of every game published since the last announcement';

    public function handle(): int
    {
        $games = Game::query()
            ->published()
            ->upcoming()
            ->notAnnounced()
            ->withCount('activeParticipants')
            ->orderBy('start_time')
            ->get()
            ->filter(fn(Game $game) => $game->active_participants_count < $game->capacity)
            ->values();

        if ($games->isEmpty()) {
            return self::SUCCESS;
        }

        DB::transaction(fn() => Game::whereIn('id', $games->pluck('id'))->update(['announced_at' => now()]));

        NotifyUsersAboutNewGames::dispatch($games->pluck('id')->all());

        Log::info("Announcing {$games->count()} new games.");

        return self::SUCCESS;
    }
}
