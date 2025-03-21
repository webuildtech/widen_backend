<?php

namespace App\Services;

use App\Models\Court;
use App\Models\IntervalPrice;
use App\Models\ReservationSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CourtSlotService
{
    public function generateFreeSlots(Court $court, Carbon $date, User $user = null): array
    {
        $interval = $court->intervals()
            ->whereDate('date_from', '<=', $date)
            ->whereDate('date_to', '>=', $date)
            ->orderByPivot('order')
            ->first();

        if (!$interval) {
            return [];
        }

        $prices = $this->filterPricesByDate($interval, $date);
        $reservedSlots = $this->getReservedSlots($date, $court);

        return $this->calculateAvailableSlots($prices, $court, $date, $reservedSlots, $user);
    }

    private function filterPricesByDate($interval, Carbon $date): Collection
    {
        $query = $interval->prices()->where('day', strtolower($date->format('D')));

        if ($date->isToday()) {
            $query->where('end_time', '>=', now()->format('H:i'));
        }

        return $query->get();
    }

    private function getReservedSlots(Carbon $date, Court $court): array
    {
        return ReservationSlot::whereDate('slot_start', $date)
            ->where('court_id', $court->id)
            ->where('try_sell', false)
            ->where('is_refunded', false)
            ->pluck('slot_end', 'slot_start')
            ->toArray();
    }

    private function calculateAvailableSlots(Collection $prices, Court $court, Carbon $date, array $reservedSlots, User $user = null): array
    {
        return $prices->flatMap(function (IntervalPrice $price) use ($user, $court, $date, $reservedSlots) {
            $startTime = Carbon::parse("{$date->format('Y-m-d')} {$price->start_time}");
            $endTime = Carbon::parse("{$date->format('Y-m-d')} {$price->end_time}");

            return $this->generateTimeSlots($startTime, $endTime, $price, $court, $reservedSlots, $user);
        })->toArray();
    }

    private function findPrice(IntervalPrice $price, User $user = null): array
    {
        $specialPrice = $user ? $price->groups()
            ->where(function ($query) use ($user) {
                $query->whereHas('users', fn($query) => $query->where('users.id', $user->id));

                if ($user->subscription) {
                    $query->orWhereHas('plan', fn($query) => $query->where('plans.id', $user->subscription->plan_id));
                }
            })
            ->pluck('price')
            ->min() : null;

        return ['price' => floatval($specialPrice ?? $price->price), 'original_price' => floatval($price->price)];
    }

    private function generateTimeSlots(Carbon $startTime, Carbon $endTime, IntervalPrice $price, Court $court, array $reservedSlots, User $user = null): Collection
    {
        $prices = $this->findPrice($price, $user);

        $slots = collect();

        while ($startTime < $endTime) {
            $slotEnd = $startTime->copy()->addMinutes(30);

            if ($startTime->gt(now()) && !isset($reservedSlots[$startTime->format('Y-m-d H:i:s')])) {
                $slots->push([
                    'court_id' => $court->id,
                    'date' => $startTime->format('Y-m-d'),
                    'day' => $price->day,
                    'start_time' => $startTime->format('H:i'),
                    'end_time' => $slotEnd->format('H:i'),
                    ...$prices
                ]);
            }

            $startTime = $slotEnd;
        }

        return $slots;
    }

    public function getBestSlots(Court $court, Carbon $date, User $user = null): array
    {
        return collect($this->generateFreeSlots($court, $date, $user))->take(6)->toArray();
    }
}

