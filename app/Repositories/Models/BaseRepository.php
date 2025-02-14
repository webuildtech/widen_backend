<?php

namespace App\Repositories\Models;

use App\Interfaces\Repositories\Models\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;

class BaseRepository implements BaseRepositoryInterface
{

    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(Data $data): Model
    {
        return $this->model->create($this->getData($data));
    }

    public function update(Model $model, Data $data): Model
    {
        $model->update($this->getData($data));

        return $model->fresh();
    }

    public function delete(Model $model): bool
    {
        $model->delete();

        return true;
    }

    public function getData(Data $data, array $except = []): array
    {
        return $data->except(... $except)->all();
    }
}
