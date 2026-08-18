<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Services\Games\GameService;
use Illuminate\Console\Command;
use Log;

class CancelUnfilledGamesCommand extends Command
{
    protected $signature = 'app:cancel-unfilled-games-command';

    protected $description = 'Cancels games that have not filled up shortly before their start';

    public function __construct(
        protected GameService $gameService,
    )
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $minutes = config('games.cancel_unfilled_minutes_before');

        Game::query()
            ->published()
            ->where('start_time', '<=', now()->addMinutes($minutes))
            ->withCount(['participants as confirmed_participants_count' => fn($query) => $query->confirmed()])
            ->get()
            ->filter(fn(Game $game) => $game->confirmed_participants_count < $game->capacity)
            ->each(function (Game $game) {
                $this->gameService->cancel($game, __('games.canceled_not_filled'));

                Log::info("Game {$game->id} did not fill up, canceled and refunded.");
            });

        return self::SUCCESS;
    }
}
