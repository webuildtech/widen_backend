<?php

namespace App\Services;

use App\Data\User\Reservations\ReservationSlotData;
use App\Models\Court;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReservationSlotService
{
    public function __construct(
        protected CourtSlotService $courtSlotService
    ) {
    }

    /** @var $slots Collection<int, ReservationSlotData> */
    public function isAllFree(Collection $slots, User $user = null): array
    {
        $occupySlots = [];
        $freeSlots = [];

        $slotsByDates = $slots->sortBy([['date', 'asc'], ['start_time', 'asc']])
            ->groupBy('court_id')
            ->map(fn ($dates) => $dates->groupBy('date'))
            ->all();

        foreach ($slotsByDates as $courtId => $slotsByDate) {
            $court = Court::findOrFail($courtId);

            foreach ($slotsByDate as $date => $slots) {
                $availableSlots = collect($this->courtSlotService->generateFreeSlots($court, Carbon::parse($date), $user));

                foreach ($slots as $slot) {
                    $availableSlot = $availableSlots->filter(fn($available) =>
                        $available['start_time'] === $slot->start_time && $available['end_time'] === $slot->end_time
                    )->first();

                    $availableSlot ? $freeSlots[] = $availableSlot : $occupySlots[] = $slot;
                }
            }
        }

        return ['occupy' => $occupySlots, 'free' => $freeSlots];
    }

    /** @var $slots Collection<int, ReservationSlotData> */
    public function isAllAvailable(Collection $slots): bool
    {
        $slotsByDates = $slots->groupBy('court_id')->map(fn ($dates) => $dates->groupBy('date'))->toArray();

        foreach ($slotsByDates as $slotsByDate) {
            foreach ($slotsByDate as $slots) {
                $filteredSlots = collect();

                foreach ($slots as $slot) {
                    if (!$this->haveDuplicate($filteredSlots, $slot)) {
                        $filteredSlots->push($slot);
                    } else {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    private function haveDuplicate($slots, $newSlot): bool
    {
        foreach ($slots as $slot) {
            if ($newSlot['start_time'] === $slot['start_time'] && $newSlot['end_time'] === $slot['end_time']) {
                return true;
            }
        }

        return false;
    }
}

