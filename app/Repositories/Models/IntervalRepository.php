<?php

namespace App\Repositories\Models;

use App\Interfaces\Repositories\Models\IntervalRepositoryInterface;
use App\Models\Interval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
            $this->syncPrices($interval, $data->prices);
        }

        return $interval->refresh();
    }

    public function update(Model $model, Data $data): Model
    {
        $values = $this->getData($data, ['prices']);

        isset($values['date_from']) && $values['date_from']->startOfDay();
        if (isset($values['date_to'])) {
            $values['date_to']->endOfDay();

            if ($values['date_to'] < now()) {
                DB::table('court_interval')->where('interval_id', $model->id)->delete();
            }
        }

        $model->update($values);

        if ($data->prices instanceof Collection) {
            $model->prices()->delete();

            $this->syncPrices($model, $data->prices);
        }

        return $model->fresh();
    }

    private function syncPrices(Interval $interval, $prices)
    {
        foreach ($prices as $price) {
            $priceCreate = $interval->prices()->create($this->getData($price, ['groups']));

            $groupsPrices = [];

            foreach ($price->groups as $group) {
                $groupsPrices[$group->group_id] = ['price' => $group->price];
            }

            $priceCreate->groups()->sync($groupsPrices);
        }
    }
}
