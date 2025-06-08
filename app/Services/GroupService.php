<?php

namespace App\Services;

use App\Data\Admin\Groups\StoreGroupData;
use App\Data\Admin\Groups\UpdateGroupData;
use App\Models\Group;

class GroupService
{
    public function create(StoreGroupData $data): Group
    {
        $group = Group::create($data->except('users_ids')->all());

        $this->syncUsers($group, $data->users_ids);

        return $group->refresh();
    }

    public function update(Group $group, UpdateGroupData $data): Group
    {
        $group->update($data->except('users_ids')->all());

        $this->syncUsers($group, $data->users_ids);

        return $group->fresh();
    }

    private function syncUsers(Group $group, $users): void
    {
        if (is_array($users)) {
            $group->users()->sync($users);
        }
    }
}
