<?php

namespace App\Interfaces\Repositories\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;

interface BaseRepositoryInterface
{
    public function create(Data $data): Model;

    public function update(Model $model, Data $data): Model;

    public function delete(Model $model): bool;

    public function getData(Data $data, array $except = []): array;
}
