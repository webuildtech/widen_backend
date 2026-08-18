<?php

namespace App\Services\Games;

use App\Data\Admin\Games\GameSlotConflictData;
use App\Enums\GameConflictReason;
use App\Models\Court;
use App\Models\Interval;
use App\Repositories\IntervalRepository;
use App\Repositories\ReservationRepository;
use App\Services\Slots\DowntimeSlotService;
use App\Services\Slots\SlotService;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class GameAvailabilityService
{
    public function __construct(
        protected SlotService            $slotService,
        protected DowntimeSlotService    $downtimeSlotService,
        protected IntervalRepository     $intervalRepository,
        protected ReservationRepository  $reservationRepository,
    )
    {
    }

    /**
     * Every half hour of the requested range that cannot host a game, with the reason why.
     * An empty collection means the court is free.
     *
     * @return Collection<int, GameSlotConflictData>
     */
    public function conflicts(
        Court   $court,
        Carbon  $date,
        string  $startTime,
        string  $endTime,
        ?int    $skipReservationId = null,
    ): Collection
    {
        $slots = $this->slotService->generate($startTime, $endTime);
        $interval = $this->intervalRepository->getForCourtAndDateFirst($court, $date);

        if (!$interval) {
            return $slots->map(fn(array $slot) => $this->conflict($slot, GameConflictReason::COURT_CLOSED));
        }

        $openSlots = $this->openSlots($interval, $date);
        $downtimeSlots = $this->downtimeSlotService->getForCourtAndDate($court, $date);
        $reservedSlots = $this->reservationRepository->getReservedSlotsForCourtAndDate($court, $date, $skipReservationId);

        return $slots
            ->map(function (array $slot) use ($date, $openSlots, $downtimeSlots, $reservedSlots) {
                $key = $slot['start_time'];

                return match (true) {
                    $this->slotEnd($date, $key)->isPast() => $this->conflict($slot, GameConflictReason::IN_PAST),
                    !isset($openSlots[$key]) => $this->conflict($slot, GameConflictReason::COURT_CLOSED),
                    isset($downtimeSlots[$key]) => $this->conflict($slot, GameConflictReason::DOWNTIME),
                    isset($reservedSlots[$key]) => $this->conflict($slot, GameConflictReason::RESERVED),
                    default => null,
                };
            })
            ->filter()
            ->values();
    }

    /**
     * Half hours the court is open on that weekday, keyed by start time.
     */
    private function openSlots(Interval $interval, Carbon $date): array
    {
        return $interval->prices()
            ->where('day', strtolower($date->format('D')))
            ->get()
            ->flatMap(fn($price) => $this->slotService->generate($price->start_time, $price->end_time))
            ->pluck('end_time', 'start_time')
            ->all();
    }

    private function slotEnd(Carbon $date, string $startTime): Carbon
    {
        return $date->copy()->setTimeFromTimeString($startTime)->addMinutes(30);
    }

    private function conflict(array $slot, GameConflictReason $reason): GameSlotConflictData
    {
        return new GameSlotConflictData($slot['start_time'], $slot['end_time'], $reason);
    }
}
