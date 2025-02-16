<?php

namespace App\Repositories\Models;

use App\Interfaces\Repositories\Models\CourtRepositoryInterface;
use App\Models\Court;
use App\Traits\LogoTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class CourtRepository extends BaseRepository implements CourtRepositoryInterface
{
    use LogoTrait;

    public function __construct(Court $model)
    {
        parent::__construct($model);
    }

    public function create(Data $data): Court
    {
        $court = $this->model->create($this->getData($data, ['logoFile', 'intervals_ids']));

        $this->saveLogo($court, $data);

        $this->syncIntervals($court, $data->intervals_ids);

        return $court->refresh();
    }

    public function update(Model $model, Data $data): Model
    {
        $model->update($this->getData($data, ['logoFile', 'deleteLogo', 'intervals_ids']));

        $this->saveLogo($model, $data);

        $this->syncIntervals($model, $data->intervals_ids);

        return $model->fresh();
    }

    private function syncIntervals(Court $court, $intervals): void
    {
        if (is_array($intervals)) {
            $ids = [];

            foreach ($intervals as $key => $interval) {
                $ids[$interval] = ['order' => $key];
            }

            $court->intervals()->sync($ids);
        }
    }
}
