<?php

namespace App\Services\Slots;

use App\Models\Court;
use App\Models\IntervalPrice;
use App\Models\User;
use App\Repositories\IntervalRepository;
use App\Repositories\ReservationRepository;
use App\Services\Checkers\CourtSlotAvailabilityChecker;
use App\Services\IntervalPriceService;
use App\Services\IntervalService;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CourtSlotService
{
    public function __construct(
        protected SlotService                  $slotService,
        protected DowntimeSlotService          $downtimeSlotService,
        protected IntervalService              $intervalService,
        protected IntervalPriceService         $intervalPriceService,
        protected IntervalRepository           $intervalRepository,
        protected ReservationRepository        $reservationRepository,
        protected CourtSlotAvailabilityChecker $courtSlotAvailabilityChecker
    )
    {
    }

    public function generateFreeSlots(Court $court, Carbon $date, User $user = null): Collection
    {
        if (!$interval = $this->intervalRepository->getForCourtAndDateFirst($court, $date)) {
            return collect();
        }

        $intervalPrices = $this->intervalService->getPricesByDay($interval, $date);
        $reservedSlots = $this->reservationRepository->getReservedSlotsForCourtAndDate($court, $date);
        $downtimeSlots = $this->downtimeSlotService->getForCourtAndDate($court, $date);

        return $this->calculateAvailableSlots($intervalPrices, $court, $date, $reservedSlots, $downtimeSlots, $user);
    }

    private function calculateAvailableSlots(Collection $intervalPrices, Court $court, Carbon $date, array $reservedSlots, array $downtimeSlots, User $user = null): Collection
    {
        $slots = collect();

        foreach ($intervalPrices as $intervalPrice) {
            $specialPrice = $user
                ? $this->intervalPriceService->getPriceForUser($user, $intervalPrice)
                : floatval($intervalPrice->price);

            $generatedSlots = $this->slotService->generate($intervalPrice->start_time, $intervalPrice->end_time);

            foreach ($generatedSlots as $slot) {
                $slotDateTime = $date->copy()->setTimeFromTimeString($slot['start_time']);

                if ($this->courtSlotAvailabilityChecker->isAvailable($slotDateTime, $reservedSlots, $downtimeSlots)) {
                    $slots->push(
                        $this->createSlot($court, $intervalPrice, $slotDateTime, $slot, $specialPrice)
                    );
                }
            }
        }

        return $slots;
    }

    private function createSlot(Court $court, IntervalPrice $price, Carbon $slotDateTime, array $slot, float $userPrice): array
    {
        return [
            'court_id' => $court->id,
            'date' => $slotDateTime->format('Y-m-d'),
            'day' => $price->day,
            'start_time' => $slot['start_time'],
            'end_time' => $slot['end_time'],
            'price' => $userPrice,
            'original_price' => floatval($price->price),
        ];
    }
}

