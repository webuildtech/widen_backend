<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Groups\GroupData;
use App\Data\Admin\Groups\ListGroupData;
use App\Data\Admin\Groups\SelectGroupData;
use App\Data\Admin\Groups\StoreGroupData;
use App\Data\Admin\Groups\UpdateGroupData;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Services\GroupService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class GroupController extends Controller
{
    public function __construct(
        protected GroupService $groupService
    )
    {
    }

    public function index()
    {
        $groups = QueryBuilder::for(Group::class)
            ->allowedSorts([
                'name',
                'plan_id',
                'updated_at'
            ])
            ->allowedFilters([
                'name',
                AllowedFilter::exact('plan_id'),
                AllowedFilter::scope('updated_at_between'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return ListGroupData::collect($groups);
    }

    public function store(StoreGroupData $data): GroupData
    {
        $group = $this->groupService->create($data);

        return GroupData::from($group);
    }

    public function show(Group $group): GroupData
    {
        return GroupData::from($group);
    }

    public function update(UpdateGroupData $data, Group $group): GroupData
    {
        $group = $this->groupService->update($group, $data);

        return GroupData::from($group);
    }

    public function destroy(Group $group): array
    {
        $group->delete();

        return [];
    }

    public function all()
    {
        return SelectGroupData::collect(Group::all());
    }
}
