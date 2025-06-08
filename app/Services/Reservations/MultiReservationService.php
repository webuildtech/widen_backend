<?php

namespace App\Services\Reservations;

use App\Data\Admin\Reservations\MultiReservationResult;
use App\Data\Admin\Reservations\StoreMultiReservationData;
use App\Data\Admin\Reservations\StoreTimeBlockData;
use App\Models\User;
use App\Repositories\CourtRepository;
use App\Services\Slots\CourtSlotService;
use App\Services\Slots\ReservationSlotService;
use App\Services\Slots\SlotService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class MultiReservationService
{
    public function __construct(
        protected SlotService            $slotService,
        protected CourtSlotService       $courtSlotService,
        protected ReservationSlotService $reservationSlotService,
        protected ReservationService     $reservationService,
        protected CourtRepository        $courtRepository,
    )
    {
    }

    public function store(StoreMultiReservationData $data): MultiReservationResult
    {
        $weeklyTimeBlocks = $this->expandTimeBlocksToSlots($data);
        $matchedDates = $this->generateMatchedDates($data, $weeklyTimeBlocks);
        $courts = $this->courtRepository->get($data->court_type, $data->court_id);

        [$freeSlots, $blockedSlots] = $this->findFreeSlots($matchedDates, $courts);

        if ($blockedSlots->isNotEmpty() && !$data->force_create) {
            return MultiReservationResult::blocked($blockedSlots);
        }

        $user = User::find($data->user_id);

        foreach ($freeSlots as $time) {
            $this->reservationService->createWithSlots(
                $user,
                [
                    'start_time' => $time['start_time'],
                    'end_time' => $time['end_time'],
                    'court_id' => $time['court_id'],
                    'delete_after_failed_payment' => false
                ],
                $time['slots'],
            );
        }

        return MultiReservationResult::success($freeSlots);
    }

    protected function expandTimeBlocksToSlots(StoreMultiReservationData $data): Collection
    {
        return $data->time_blocks->map(fn(StoreTimeBlockData $time) => [
            ...$time->toArray(),
            'slots' => $this->slotService->generate($time->start_time, $time->end_time)
        ]);
    }

    protected function generateMatchedDates(StoreMultiReservationData $data, Collection $weeklyTimeBlocks): Collection
    {
        return collect(CarbonPeriod::create($data->date_from, $data->date_to))
            ->flatMap(fn($date) => $weeklyTimeBlocks
                ->filter(fn($weeklyTimeBlock) => $weeklyTimeBlock['day'] === strtolower($date->format('D')))
                ->map(fn($weeklyTimeBlock) => ['date' => $date->format('Y-m-d'), ...$weeklyTimeBlock])
            );
    }

    protected function findFreeSlots(Collection $matchedDates, Collection $courts): array
    {
        $busySlots = collect();
        $availableSlots = collect();

        foreach ($matchedDates as $date) {
            $isAvailable = false;

            foreach ($courts as $court) {
                [$freeSlots, $occupySlots] = $this->reservationSlotService->getFreeAndOccupied(
                    $court,
                    Carbon::parse($date['date']),
                    $date['slots']
                );

                if ($freeSlots->count() === $date['slots']->count()) {
                    $availableSlots->push([
                        'start_time' => Carbon::parse($date['date'] . ' ' . $date['start_time']),
                        'end_time' => Carbon::parse($date['date'] . ' ' . $date['end_time']),
                        'court_id' => $court->id,
                        'slots' => $freeSlots,
                    ]);

                    $isAvailable = true;
                    break;
                }
            }

            if (!$isAvailable) {
                $busySlots->push([
                    'date' => $date['date'],
                    'start_time' => $date['start_time'],
                    'end_time' => $date['end_time']
                ]);
            }
        }

        return [$availableSlots, $busySlots];
    }
}
