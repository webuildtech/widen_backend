<?php

namespace App\Http\Controllers\User;

use App\Data\User\Games\GameDetailData;
use App\Data\User\Games\GameFilterData;
use App\Data\User\Games\GameJoinData;
use App\Data\User\Games\GameListData;
use App\Data\User\Games\MyGameData;
use App\Data\User\Games\MyGameFilterData;
use App\Enums\GameParticipantStatus;
use App\Enums\GameStatus;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Services\Games\GameJoinService;
use Spatie\LaravelData\Optional;

class GameController extends Controller
{
    public function __construct(
        protected GameJoinService $gameJoinService,
    )
    {
    }

    public function index(GameFilterData $data)
    {
        $games = Game::query()
            ->published()
            ->upcoming()
            ->with(['courtType', 'court'])
            ->withCount('activeParticipants')
            ->orderBy('start_time');

        if (!$data->court_type_id instanceof Optional) {
            $games->where('court_type_id', $data->court_type_id);
        }

        if (!$data->date_from instanceof Optional) {
            $games->whereDate('start_time', '>=', $data->date_from);
        }

        if (!$data->date_to instanceof Optional) {
            $games->whereDate('start_time', '<=', $data->date_to);
        }

        if ($data->only_available === true) {
            $games->havingRaw('active_participants_count < capacity');
        }

        return GameListData::collect(
            $games->paginate(config('games.per_page'))->appends(request()->query())
        );
    }

    public function show(Game $game): GameDetailData
    {
        return GameDetailData::from($game);
    }

    public function my(MyGameFilterData $data)
    {
        $userId = auth()->id();

        $participations = auth()->user()->gameParticipations()
            ->with([
                'game.courtType',
                'game.court',
                'game.participants' => fn($query) => $query->where('added_by_user_id', $userId)->with('level'),
                'level',
                'addedBy',
            ]);

        match ($data->type) {
            'past' => $participations
                ->confirmed()
                ->whereHas('game', fn($query) => $query->where('end_time', '<=', now())->published()),
            'canceled' => $participations
                ->where(fn($query) => $query
                    ->where('status', GameParticipantStatus::CANCELED)
                    ->orWhereHas('game', fn($game) => $game->where('status', GameStatus::CANCELED))),
            default => $participations
                ->confirmed()
                ->whereHas('game', fn($query) => $query->where('end_time', '>', now())->published()),
        };

        $participations = $participations
            ->get()
            ->sortBy(fn($participation) => $participation->game->start_time)
            ->values();

        return MyGameData::collect($participations);
    }

    public function join(GameJoinData $data, Game $game)
    {
        return $this->gameJoinService
            ->join($game, auth()->user(), $data)
            ->toResponse();
    }
}
