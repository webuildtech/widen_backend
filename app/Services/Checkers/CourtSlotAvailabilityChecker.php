<?php

namespace App\Services\Checkers;

use Carbon\Carbon;

class CourtSlotAvailabilityChecker
{
    public function isAvailable(Carbon $startTime, array $reservedSlots, array $downtimeSlots): bool
    {
        return $startTime->gt(now()) &&
            !isset($reservedSlots[$startTime->format('Y-m-d H:i:s')]) &&
            !isset($downtimeSlots[$startTime->format('H:i')]);
    }
}
