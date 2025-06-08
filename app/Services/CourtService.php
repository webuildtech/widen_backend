<?php

namespace App\Services;

use App\Data\Admin\Courts\StoreCourtData;
use App\Data\Admin\Courts\UpdateCourtData;
use App\Models\Court;
use App\Traits\LogoTrait;
use Illuminate\Database\Eloquent\Model;

class CourtService
{
    use LogoTrait;

    public function create(StoreCourtData $data): Court
    {
        $court = Court::create($data->except('logoFile', 'intervals_ids')->all());

        $this->saveLogo($court, $data);

        $this->syncIntervals($court, $data->intervals_ids);

        return $court->refresh();
    }

    public function update(Court $court, UpdateCourtData $data): Model
    {
        $court->update($data->except('logoFile', 'deleteLogo', 'intervals_ids')->all());

        $this->saveLogo($court, $data);

        $this->syncIntervals($court, $data->intervals_ids);

        return $court->fresh();
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
