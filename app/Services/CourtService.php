<?php

namespace App\Services;

use App\Data\Admin\Courts\CourtStoreData;
use App\Data\Admin\Courts\CourtUpdateData;
use App\Models\Court;
use App\Services\Media\LogoManager;
use Illuminate\Database\Eloquent\Model;

class CourtService
{
    public function __construct(
        protected LogoManager $logoManager
    )
    {
    }

    public function create(CourtStoreData $data): Court
    {
        $court = Court::create($data->except('logoFile', 'intervals_ids')->all());

        $this->logoManager->handle($court, $data);

        $this->syncIntervals($court, $data->intervals_ids);

        return $court->refresh();
    }

    public function update(Court $court, CourtUpdateData $data): Model
    {
        $court->update($data->except('logoFile', 'deleteLogo', 'intervals_ids')->all());

        $this->logoManager->handle($court, $data);

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
