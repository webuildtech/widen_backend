<?php

namespace App\Services\Availability;

use App\Models\AvailabilitySlot;
use App\Models\Reservation;
use App\Models\ReservationSlot;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AvailabilitySlotService
{
    public function refreshByReservationSlot(ReservationSlot $slot): void
    {
        $key = $this->makeKeyFromSlot($slot);

        $this->refreshByKeys([$key]);
    }

    public function refreshByKeys(array $keys): void
    {
        $keys = collect($keys)
            ->filter(fn($key) => isset($key['court_id'], $key['date'], $key['start_key']))
            ->unique(fn($key) => $key['court_id'] . '|' . $key['date'] . '|' . $key['start_key'])
            ->values();

        if ($keys->isEmpty()) {
            return;
        }

        $reserved = [];
        foreach ($keys as $key) {
            $start = Carbon::parse("{$key['date']} {$key['start_key']}:00");
            $end = (clone $start)->addMinute();

            $reserved[$key['court_id'] . '|' . $key['date'] . '|' . $key['start_key']] = ReservationSlot::query()
                ->active()
                ->where('court_id', $key['court_id'])
                ->where('slot_start', '>=', $start)
                ->where('slot_start', '<', $end)
                ->exists();
        }

        $courtIds = $keys->pluck('court_id')->unique()->values();
        $dates = $keys->pluck('date')->unique()->values();
        $startKeys = $keys->pluck('start_key')->unique()->values();

        $affected = AvailabilitySlot::query()
            ->whereIn('court_id', $courtIds)
            ->whereIn('date', $dates)
            ->whereIn('start_time', $startKeys)
            ->get(['id', 'court_id', 'date', 'start_time']);

        foreach ($affected as $row) {
            $rk = $row->court_id . '|' . $row->date . '|' . $row->start_time;

            if (isset($reserved[$rk])) {
                $row->update(['is_reserved' => $reserved[$rk]]);
            }
        }
    }

    public function makeKeyFromSlot(ReservationSlot $slot): array
    {
        return [
            'court_id'  => (int) $slot->court_id,
            'date'      => $slot->slot_start->toDateString(),
            'start_key' => $slot->slot_start->format('H:i'),
        ];
    }

    public function makeKey(int $courtId, $slotStart): array
    {
        $dt = $slotStart instanceof Carbon ? $slotStart : Carbon::parse($slotStart);

        return [
            'court_id'  => $courtId,
            'date'      => $dt->toDateString(),
            'start_key' => $dt->format('H:i'),
        ];
    }

    protected function uniqueKeys(iterable $keys): Collection
    {
        return collect($keys)
            ->filter(fn($k) => isset($k['court_id'], $k['date'], $k['start_key']))
            ->unique(fn($k) => $k['court_id'].'|'.$k['date'].'|'.$k['start_key'])
            ->values();
    }

    public function collectKeysFromReservation(Reservation $reservation): Collection
    {
        $rows = $reservation->slots()->get(['court_id', 'slot_start']);

        return $this->collectKeysFromSlotRows($rows);
    }

    protected function collectKeysFromSlotRows(iterable $rows): Collection
    {
        $keys = collect($rows)->map(fn($r) => $this->makeKey($r->court_id, $r->slot_start));

        return $this->uniqueKeys($keys);
    }
}
