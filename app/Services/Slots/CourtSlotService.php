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
        protected PlanCourtTypeRuleSlotService $planSlotService,
        protected IntervalService              $intervalService,
        protected IntervalPriceService         $intervalPriceService,
        protected IntervalRepository           $intervalRepository,
        protected ReservationRepository        $reservationRepository,
        protected CourtSlotAvailabilityChecker $courtSlotAvailabilityChecker
    )
    {
    }

    public function generateFreeSlots(Court $court, Carbon $date, User $user = null, bool $checkByPlan = true, int $skipReservation = null): Collection
    {
        if (!$interval = $this->intervalRepository->getForCourtAndDateFirst($court, $date)) {
            return collect();
        }

        $intervalPrices = $this->intervalService->getPricesByDay($interval, $date);
        $reservedSlots = $this->reservationRepository->getReservedSlotsForCourtAndDate($court, $date, $skipReservation);
        $downtimeSlots = $this->downtimeSlotService->getForCourtAndDate($court, $date);
        $planSlots = $checkByPlan ? $this->planSlotService->getForDateUserAndCourtType($court->courtType, $date, $user) : null;
        $slotsForSale = $this->reservationRepository->getSlotForSaleByCourtAndDate($court, $date);

        return $this->calculateAvailableSlots($intervalPrices, $court, $date, $reservedSlots, $downtimeSlots, $planSlots, $user, $slotsForSale);
    }

    private function calculateAvailableSlots(
        Collection $intervalPrices,
        Court      $court,
        Carbon     $date,
        array      $reservedSlots,
        array      $downtimeSlots,
        array      $planSlots = null,
        User       $user = null,
        array      $slotsForSale = []
    ): Collection
    {
        $slots = collect();

        foreach ($intervalPrices as $intervalPrice) {
            $specialPrice = $user
                ? $this->intervalPriceService->getPriceForUser($user, $intervalPrice)
                : floatval($intervalPrice->price);

            $generatedSlots = $this->slotService->generate($intervalPrice->start_time, $intervalPrice->end_time);

            foreach ($generatedSlots as $slot) {
                $slotDateTime = $date->copy()->setTimeFromTimeString($slot['start_time']);
                $slotEndDateTime = $date->copy()->setTimeFromTimeString($slot['end_time']);

                if ($this->courtSlotAvailabilityChecker->isAvailable($slotDateTime, $slotEndDateTime, $reservedSlots, $downtimeSlots, $planSlots)) {
                    $key = $slotDateTime->format('H:i');

                    $slots->push(
                        $this->createSlot(
                            $court,
                            $intervalPrice,
                            $slotDateTime,
                            $slot,
                            $specialPrice,
                            isset($slotsForSale[$key]) ? 'sale' : 'standard'
                        )
                    );
                }
            }
        }

        return $slots;
    }

    private function createSlot(Court $court, IntervalPrice $price, Carbon $slotDateTime, array $slot, float $userPrice, string $type): array
    {
        return [
            'court_id' => $court->id,
            'date' => $slotDateTime->format('Y-m-d'),
            'day' => $price->day,
            'start_time' => $slot['start_time'],
            'end_time' => $slot['end_time'],
            'price' => $userPrice,
            'original_price' => floatval($price->price),
            'type' => $type,
        ];
    }
}

