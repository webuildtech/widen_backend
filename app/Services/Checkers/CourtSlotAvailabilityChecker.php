<?php

namespace App\Services\Checkers;

use Carbon\Carbon;

class CourtSlotAvailabilityChecker
{
    public function isAvailable(Carbon $startTime, array $reservedSlots, array $downtimeSlots, array $planSlots = null): bool
    {
        $key = $startTime->format('H:i');

        return $startTime->gt(now()) &&
            !isset($reservedSlots[$key]) &&
            !isset($downtimeSlots[$key]) &&
            ($planSlots === null || isset($planSlots[$key]));
    }
}
