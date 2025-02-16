<?php

namespace App\Repositories\Models;

use App\Interfaces\Repositories\Models\GroupRepositoryInterface;
use App\Models\Group;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;

class GroupRepository extends BaseRepository implements GroupRepositoryInterface
{
    public function __construct(Group $model)
    {
        parent::__construct($model);
    }

    public function create(Data $data): Group
    {
        $group = $this->model->create($this->getData($data, ['users_ids']));

        $this->syncUsers($group, $data->users_ids);

        return $group->refresh();
    }

    public function update(Model $model, Data $data): Model
    {
        $model->update($this->getData($data, ['users_ids']));

        $this->syncUsers($model, $data->users_ids);

        return $model->fresh();
    }

    private function syncUsers(Group $group, $users): void
    {
        if (is_array($users)) {
            $group->users()->sync($users);
        }
    }
}
