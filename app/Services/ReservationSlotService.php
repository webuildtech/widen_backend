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
        $index = 0;

        $slots->groupBy('court_id')->each(function (Collection $slots, $courtId) use ($user, &$occupySlots, &$freeSlots, &$index) {
           $court = Court::findOrFail($courtId);

           $slots->each(function (ReservationSlotData $slot) use ($user, $court, &$occupySlots, &$freeSlots, &$index) {
               $freeSlots[$index] = [...$slot->toArray(), 'slots' => []];

               $startTime = Carbon::parse($slot->date . ' ' . $slot->start_time);
               $endTime = Carbon::parse($slot->date . ' ' . $slot->end_time);

               $availableSlots = collect($this->courtSlotService->generateFreeSlots($court, $startTime, $user));

               $currentTime = $startTime->copy();

               while ($currentTime < $endTime) {
                   $availableSlot = $availableSlots->filter(fn($available) =>
                       $available['start_time'] === $currentTime->format('H:i') &&
                       $available['end_time'] === $currentTime->copy()->addMinutes(30)->format('H:i')
                   )->first();

                   if ($availableSlot) {
                       $freeSlots[$index]['slots'][] = $availableSlot;
                   } else {
                       $occupySlots[] = [
                           'court_id' => $court->id,
                           'date' => $slot->date,
                           'start_time' => $slot->start_time,
                           'end_time' => $slot->end_time,
                       ];

                       break;
                   }

                   $currentTime->addMinutes(30);
               }

               $index++;
           });
        });

        return ['occupy' => $occupySlots, 'free' => $freeSlots];
    }

    /** @var $slots Collection<int, ReservationSlotData> */
    public function isAllAvailable(Collection $slots): bool
    {
        $grouped = $slots->groupBy('court_id')->map(fn ($dates) => $dates->groupBy('date'))->toArray();

        foreach ($grouped as $dates) {
            foreach ($dates as $slots) {
                $filteredSlots = collect();

                foreach ($slots as $slot) {
                    if (!$this->isOverlapping($filteredSlots, $slot)) {
                        $filteredSlots->push($slot);
                    } else {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    private function isOverlapping($slots, $newSlot): bool
    {
        foreach ($slots as $slot) {
            if ($newSlot['start_time'] <= $slot['end_time'] && $newSlot['end_time'] >= $slot['start_time']) {
                return true;
            }
        }

        return false;
    }
}

