<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\GameGroups\GameGroupData;
use App\Data\Admin\GameGroups\GameGroupListData;
use App\Data\Admin\GameGroups\GameGroupSelectOptionData;
use App\Data\Admin\GameGroups\GameGroupStoreData;
use App\Data\Admin\GameGroups\GameGroupUpdateData;
use App\Http\Controllers\Controller;
use App\Models\GameGroup;
use App\Services\Games\GameGroupService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class GameGroupController extends Controller
{
    public function __construct(
        protected GameGroupService $gameGroupService,
    )
    {
    }

    public function index()
    {
        $gameGroups = QueryBuilder::for(GameGroup::class)
            ->withCount('games')
            ->defaultSort('sort_order', 'id')
            ->allowedSorts([
                'sort_order',
                'active',
                'games_count',
                'updated_at',
            ])
            ->allowedFilters([
                AllowedFilter::exact('active'),
                AllowedFilter::scope('updated_at_between'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return GameGroupListData::collect($gameGroups);
    }

    public function store(GameGroupStoreData $data): GameGroupData
    {
        return GameGroupData::from($this->gameGroupService->create($data));
    }

    public function show(GameGroup $gameGroup): GameGroupData
    {
        return GameGroupData::from($gameGroup);
    }

    public function update(GameGroupUpdateData $data, GameGroup $gameGroup): GameGroupData
    {
        return GameGroupData::from($this->gameGroupService->update($gameGroup, $data));
    }

    public function destroy(GameGroup $gameGroup): array
    {
        $this->gameGroupService->delete($gameGroup);

        return [];
    }

    public function all()
    {
        return GameGroupSelectOptionData::collect(GameGroup::ordered()->get());
    }
}
