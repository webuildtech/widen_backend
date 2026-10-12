<?php

namespace App\Console\Commands;

use App\Enums\GameStatus;
use App\Models\Game;
use Illuminate\Console\Command;

class FinishGamesCommand extends Command
{
    protected $signature = 'app:finish-games-command';

    protected $description = 'Marks published games that have ended as finished';

    public function handle(): int
    {
        Game::query()
            ->published()
            ->where('end_time', '<=', now())
            ->update(['status' => GameStatus::FINISHED]);

        return self::SUCCESS;
    }
}
