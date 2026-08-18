<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Games\GameCancelData;
use App\Data\Admin\Games\GameData;
use App\Data\Admin\Games\GameListData;
use App\Data\Admin\Games\GameStoreData;
use App\Data\Admin\Games\GameUpdateData;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameParticipant;
use App\Services\Games\GameRefundService;
use App\Services\Games\GameService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class GameController extends Controller
{
    public function __construct(
        protected GameService       $gameService,
        protected GameRefundService $gameRefundService,
    )
    {
    }

    public function index()
    {
        $games = QueryBuilder::for(Game::class)
            ->with(['court', 'courtType'])
            ->withCount('activeParticipants')
            ->defaultSort('-start_time')
            ->allowedSorts([
                'start_time',
                'end_time',
                'court_id',
                'capacity',
                'price_with_vat',
                'status',
                'updated_at',
            ])
            ->allowedFilters([
                AllowedFilter::exact('court_id'),
                AllowedFilter::exact('court_type_id'),
                AllowedFilter::exact('capacity'),
                AllowedFilter::exact('status'),
                AllowedFilter::scope('start_time_from'),
                AllowedFilter::scope('end_time_to'),
                AllowedFilter::scope('updated_at_between'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return GameListData::collect($games);
    }

    public function store(GameStoreData $data)
    {
        $result = $this->gameService->create($data);

        return $result->hasConflicts()
            ? response()->json(['conflicts' => $result->conflicts], 422)
            : GameData::collect($result->games);
    }

    public function show(Game $game): GameData
    {
        return GameData::from($game);
    }

    public function update(GameUpdateData $data, Game $game)
    {
        $result = $this->gameService->update($game, $data);

        return $result->hasConflicts()
            ? response()->json(['conflicts' => $result->conflicts], 422)
            : GameData::from($result->games->first());
    }

    public function cancel(GameCancelData $data, Game $game): GameData
    {
        return GameData::from($this->gameService->cancel($game, $data->reason));
    }

    public function destroy(Game $game): array
    {
        $this->gameService->delete($game);

        return [];
    }

    public function removeParticipant(Game $game, GameParticipant $participant): GameData
    {
        abort_unless($participant->game_id === $game->id, 404);

        $this->gameRefundService->refund($participant, __('games.removed_by_admin'));

        return GameData::from($game->fresh());
    }
}
