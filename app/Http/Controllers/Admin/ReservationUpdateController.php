<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Reservations\ReservationCalendarData;
use App\Data\Admin\Reservations\ReservationUpdateData;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Services\Reservations\ReservationService;
use App\Services\Slots\ReservationSlotService;
use Carbon\Carbon;

class ReservationUpdateController extends Controller
{
    public function __construct(
        protected ReservationSlotService $reservationSlotService,
        protected ReservationService          $reservationService,
    ) {
    }

    public function __invoke(ReservationUpdateData $data, Reservation $reservation)
    {
        if ($reservation->owner_type === 'game') {
            return response()->json(['message' => __('games.edit_from_games_page')], 424);
        }

        if ($reservation->is_paid) {
            return response()->json(['message' => __('reservations.cannot_edit_paid_reservation')], 424);
        }

        if ($reservation->canceled_at) {
            return response()->json(['message' => __('reservations.cannot_edit_canceled_reservation')], 424);
        }

        if (!$this->reservationSlotService->isAllAvailable($data->slots->toArray())) {
            return response()->json(['message' => __('reservations.something_went_wrong')], 424);
        }

        ['free' => $freeSlots, 'occupied' => $occupySlots] = $this->reservationSlotService->splitIntoFreeAndOccupied(
            $data->slots->toArray(),
            $reservation->owner,
            false,
            $reservation->id
        );

        if ($occupySlots->count() > 0) {
            return response()->json(['slots' => $occupySlots], 422);
        }

        $timeBlocks = $this->reservationSlotService->merge($freeSlots);

        $reservations = collect();

        foreach ($timeBlocks as $timeBlock) {
            $newReservation = $this->reservationService->createWithSlots(
                $reservation->owner,
                [
                    'start_time' => Carbon::parse($timeBlock['date'] . ' ' . $timeBlock['start_time']),
                    'end_time' => Carbon::parse($timeBlock['date'] . ' ' . $timeBlock['end_time']),
                    'court_id' => $timeBlock['court_id'],
                    'is_paid' => false,
                    'delete_after_failed_payment' => false,
                    'comment' => $reservation->comment,
                ],
                collect($timeBlock['slots']),
            );

            $newReservation->load(['court', 'owner']);

            $reservations->push($newReservation);
        }

        $this->reservationService->delete($reservation);

        return ReservationCalendarData::collect($reservations);
    }
}
