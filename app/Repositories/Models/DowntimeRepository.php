<?php

namespace App\Repositories\Models;

use App\Interfaces\Repositories\Models\DowntimeRepositoryInterface;
use App\Models\Downtime;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;

class DowntimeRepository extends BaseRepository implements DowntimeRepositoryInterface
{
    public function __construct(Downtime $model)
    {
        parent::__construct($model);
    }

    public function create(Data $data): Downtime
    {
        $group = $this->model->create($this->getData($data));

        return $group->refresh();
    }

    public function update(Model $model, Data $data): Model
    {
        $model->update($this->getData($data));

        return $model->fresh();
    }
}
