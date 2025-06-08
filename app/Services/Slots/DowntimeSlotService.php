<?php

namespace App\Services\Slots;

use App\Models\Court;
use App\Models\Downtime;
use App\Repositories\DowntimeRepository;
use Carbon\Carbon;

class DowntimeSlotService
{
    public function __construct(
        protected DowntimeRepository $downtimeRepository,
        protected SlotService        $slotService,
    )
    {
    }

    public function getForCourtAndDate(Court $court, Carbon $date): array
    {
        $downtimes = $this->downtimeRepository->getForCourtAndDate($court, $date);

        return $downtimes->flatMap(
            fn(Downtime $downtime) => $this->slotService
                ->generate($downtime->start_time, $downtime->end_time)
                ->pluck('end_time', 'start_time')
        )->toArray();
    }
}
