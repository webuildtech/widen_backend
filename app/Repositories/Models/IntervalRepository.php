<?php

namespace App\Repositories\Models;

use App\Interfaces\Repositories\Models\IntervalRepositoryInterface;
use App\Models\Interval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class IntervalRepository extends BaseRepository implements IntervalRepositoryInterface
{
    public function __construct(Interval $model)
    {
        parent::__construct($model);
    }

    public function create(Data $data): Interval
    {
        $data->date_from->startOfDay();
        $data->date_to->endOfDay();

        $interval = $this->model->create($this->getData($data, ['prices']));

        if ($data->prices instanceof Collection) {
          $interval->prices()->createMany($data->prices->toArray());
        }

        return $interval->refresh();
    }

    public function update(Model $model, Data $data): Model
    {
        $model->update($this->getData($data, ['prices']));

        $model->prices()->delete();
        $model->prices()->createMany($data->prices->toArray());

        return $model->fresh();
    }
}
