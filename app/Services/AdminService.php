<?php

namespace App\Services;

use App\Data\Admin\Admins\AdminStoreData;
use App\Data\Admin\Admins\AdminUpdateData;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Spatie\LaravelData\Optional;

class AdminService
{
    public function create(AdminStoreData $data): Admin
    {
        $attributes = $data->except('role')->all();

        $attributes['password'] = Hash::make($attributes['password']);

        $admin = Admin::create($attributes);

        $admin->assignRole($data->role);

        return $admin->fresh();
    }

    public function update(Admin $admin, AdminUpdateData $data): Model
    {
        $attributes = $data->except('role')->all();

        if (isset($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        $admin->update($attributes);

        if (!$data->role instanceof Optional) {
            $admin->syncRoles($data->role);
        }

        return $admin->fresh();
    }
}
