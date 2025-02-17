<?php

namespace App\Repositories\Models;

use App\Interfaces\Repositories\Models\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Spatie\LaravelData\Data;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function create(Data $data): User
    {
        $values = $this->getData($data);

        $values['password'] = Hash::make($values['password']);

        $user = $this->model->create($values);

        return $user->fresh();
    }

    public function update(Model $model, Data $data): Model
    {
        $values = $this->getData($data);

        isset($values['password']) && $values['password'] = Hash::make($values['password']);

        $model->update($values);

        return $model->fresh();
    }
}
