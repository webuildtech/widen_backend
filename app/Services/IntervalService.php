<?php

namespace App\Services;

use App\Data\Admin\Intervals\IntervalStoreData;
use App\Data\Admin\Intervals\IntervalUpdateData;
use App\Models\Interval;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class IntervalService
{
    public function create(IntervalStoreData $data): Interval
    {
        $data->date_from->startOfDay();
        $data->date_to->endOfDay();

        $interval = Interval::create($data->except('prices')->all());

        if ($data->prices instanceof Collection) {
            $this->syncPrices($interval, $data->prices);
        }

        return $interval->refresh();
    }

    public function update(Interval $interval, IntervalUpdateData $data): Model
    {
        $attributes = $data->except('prices')->all();

        if (isset($attributes['date_from'])) {
            $attributes['date_from']->startOfDay();
        }

        if (isset($attributes['date_to'])) {
            $attributes['date_to']->endOfDay();

            if ($attributes['date_to'] < now()) {
                DB::table('court_interval')->where('interval_id', $interval->id)->delete();
            }
        }

        $interval->update($attributes);

        if ($data->prices instanceof Collection) {
            $interval->prices()->delete();

            $this->syncPrices($interval, $data->prices);
        }

        return $interval->fresh();
    }

    private function syncPrices(Interval $interval, $prices): void
    {
        foreach ($prices as $price) {
            $priceCreate = $interval->prices()->create($price->except('groups')->all());

            $groupsPrices = [];

            foreach ($price->groups as $group) {
                $groupsPrices[$group->group_id] = ['price' => $group->price];
            }

            $priceCreate->groups()->sync($groupsPrices);
        }
    }

    public function getPricesByDay(Interval $interval, Carbon $date): Collection
    {
        $query = $interval->prices()->where('day', strtolower($date->format('D')));

        if ($date->isToday()) {
            $query->where('end_time', '>=', now()->format('H:i'));
        }

        return $query->get();
    }
}
