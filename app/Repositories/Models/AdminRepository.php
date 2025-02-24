<?php

namespace App\Repositories\Models;

use App\Interfaces\Repositories\Models\AdminRepositoryInterface;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Spatie\LaravelData\Data;

class AdminRepository extends BaseRepository implements AdminRepositoryInterface
{
    public function __construct(Admin $model)
    {
        parent::__construct($model);
    }

    public function create(Data $data): Admin
    {
        $values = $this->getData($data, ['role']);

        $values['password'] = Hash::make($values['password']);

        $user = $this->model->create($values);

        $user->assignRole($data->role);

        return $user->fresh();
    }

    public function update(Model $model, Data $data): Model
    {
        $values = $this->getData($data, ['role']);

        isset($values['password']) && $values['password'] = Hash::make($values['password']);

        $model->update($values);

        if ($data->role) {
            $model->syncRoles($data->role);
        }

        return $model->fresh();
    }
}
