<?php

namespace App\Services\Slots;

use App\Models\Court;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class ReservationSlotService
{
    public function __construct(
        protected CourtSlotService $courtSlotService
    )
    {
    }

    public function splitIntoFreeAndOccupied(array $slots, User $user = null): array
    {
        $slots = collect($slots);
        $free = collect();
        $occupied = collect();

        $courtIds = $slots->pluck('court_id')->unique()->all();
        $courts = Court::whereIn('id', $courtIds)->get()->keyBy('id');

        $slotsGroupedByCourtAndDate = $slots
            ->sortBy([['date', 'asc'], ['start_time', 'asc']])
            ->groupBy('court_id')
            ->map(fn($byDate) => $byDate->groupBy('date'))
            ->all();

        foreach ($slotsGroupedByCourtAndDate as $courtId => $slotsByDate) {
            $court = $courts[$courtId] ?? throw new ModelNotFoundException("Court $courtId not found");

            foreach ($slotsByDate as $date => $courtDaySlots) {
                $splitSlots = $this->getFreeAndOccupied($court, Carbon::parse($date), $courtDaySlots, $user);

                $free = $free->merge($splitSlots['free']);
                $occupied = $occupied->merge($splitSlots['occupied']);
            }
        }

        return ['free' => $free, 'occupied' => $occupied];
    }

    public function getFreeAndOccupied(Court $court, Carbon $date, $slots, User $user = null): array
    {
        $free = collect();
        $occupied = collect();

        $availableSlots = $this->courtSlotService->generateFreeSlots($court, $date, $user);
        $indexedSlots = $availableSlots->keyBy(fn($slot) => $this->generateSlotKey($slot));

        foreach ($slots as $slot) {
            $key = $this->generateSlotKey($slot);

            $indexedSlots->has($key)
                ? $free->push($indexedSlots->get($key))
                : $occupied->push($slot);
        }

        return ['free' => $free, 'occupied' => $occupied];
    }

    public function isAllAvailable(array $slots): bool
    {
        $seenKeys = [];

        foreach ($slots as $slot) {
            $key = $slot['court_id'] . '|' . $slot['start_time'] . '|' . $slot['end_time'];

            if (isset($seenKeys[$key])) {
                return false;
            }

            $seenKeys[$key] = true;
        }

        return true;
    }

    public function merge(Collection $slots): Collection
    {
        $groupedSlots = $slots
            ->sortBy([['date', 'asc'], ['court_id', 'asc'], ['start_time', 'asc']])
            ->groupBy(fn($slot) => $slot['date'] . '-' . $slot['court_id']);

        $mergedBlocks = collect();

        foreach ($groupedSlots as $group) {
            foreach ($group as $slot) {
                $lastIndex = $mergedBlocks->keys()->last();
                $last = $lastIndex !== null ? $mergedBlocks->get($lastIndex) : null;

                if ($last && $last['end_time'] === $slot['start_time']) {
                    $last['slots'][] = $slot;
                    $mergedBlocks->put($lastIndex, [...$last, 'end_time' => $slot['end_time']]);
                } else {
                    $mergedBlocks->push([...$slot, 'slots' => [$slot]]);
                }
            }
        }

        return $mergedBlocks;
    }

    private function generateSlotKey($slot): string
    {
        return $slot['start_time'] . '|' . $slot['end_time'];
    }
}

