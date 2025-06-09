<?php

namespace App\Services;

use App\Data\Admin\Groups\GroupStoreData;
use App\Data\Admin\Groups\GroupUpdateData;
use App\Models\Group;

class GroupService
{
    public function create(GroupStoreData $data): Group
    {
        $group = Group::create($data->except('users_ids')->all());

        $this->syncUsers($group, $data->users_ids);

        return $group->refresh();
    }

    public function update(Group $group, GroupUpdateData $data): Group
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
