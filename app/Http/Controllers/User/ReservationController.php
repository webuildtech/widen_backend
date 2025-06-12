<?php

namespace App\Http\Controllers\User;

use App\Data\User\Reservations\ReservationFilterData;
use App\Data\User\Reservations\ReservationPayData;
use App\Data\User\Reservations\ReservationData;
use App\Data\User\Reservations\ReservationStoreData;
use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\ReservationGroup;
use App\Responders\Reservations\ReservationPaymentResponder;
use App\Services\Reservations\ReservationService;
use App\Services\Slots\ReservationSlotService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Str;

class ReservationController extends Controller
{
    public function __construct(
        protected ReservationService          $reservationService,
        protected ReservationSlotService      $reservationSlotService,
        protected ReservationPaymentResponder $reservationPaymentResponder
    )
    {
    }

    public function index(ReservationFilterData $data)
    {
        $user = auth()->user();

        $reservations = $user->reservations()->with(['court', 'court.courtType']);

        if ($data->date) {
            $reservations->whereDate('start_time', $data->date);
        }

        switch ($data->type) {
            case 'unpaid':
                $reservations->where('is_paid', false)
                    ->where('delete_after_failed_payment', false)
                    ->where('end_time', '>', now())
                    ->orderBy('start_time');
                break;
            case 'active':
                $reservations->where('is_paid', true)
                    ->where('end_time', '>', now())
                    ->whereNull('canceled_at')
                    ->orderBy('start_time');
                break;
            case 'past':
                $reservations->where('is_paid', true)
                    ->with('slots')
                    ->where('end_time', '<=', now())
                    ->whereNull('canceled_at')
                    ->orderBy('end_time', 'desc');
                break;
            case 'cancelled':
                $reservations->where('is_paid', true)
                    ->whereNotNull('canceled_at')
                    ->orderBy('canceled_at', 'desc');
        }

        return ReservationData::collect($reservations->get());
    }

    public function store(ReservationStoreData $data): JsonResponse
    {
        $owner = auth()->guard('user')->user();

        if (!$this->reservationSlotService->isAllAvailable($data->slots->toArray())) {
            return response()->json(['error' => 'Įvyko klaida, bandykite dar kartą!'], 424);
        }

        ['free' => $freeSlots, 'occupied' => $occupySlots] = $this->reservationSlotService->splitIntoFreeAndOccupied(
            $data->slots->toArray(),
            $owner
        );

        if ($occupySlots->count() > 0) {
            return response()->json(['slots' => $occupySlots], 423);
        }

        if (!$owner) {
            $owner = Guest::firstOrCreate($data->guest->toArray(), ['uuid' => Str::uuid()]);
        }

        $timeBlocks = $this->reservationSlotService->merge($freeSlots);

        $reservationGroup = ReservationGroup::create();

        foreach ($timeBlocks as $timeBlock) {
            $this->reservationService->createWithSlots(
                $owner,
                [
                    'start_time' => Carbon::parse($timeBlock['date'] . ' ' . $timeBlock['start_time']),
                    'end_time' => Carbon::parse($timeBlock['date'] . ' ' . $timeBlock['end_time']),
                    'court_id' => $timeBlock['court_id'],
                    'reservation_group_id' => $reservationGroup->id,
                ],
                collect($timeBlock['slots']),
            );
        }

        $response = $this->reservationPaymentResponder->handle($reservationGroup, $owner);

        return $response->toResponse();
    }

    public function cancel(Reservation $reservation)
    {
        $user = auth()->user();

        try {
            $result = $this->reservationService->cancelByUser($user, $reservation);
            return response()->json($result);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 406);
        }
    }

    public function pay(ReservationPayData $data)
    {
        $user = auth()->user();

        $reservationGroup = ReservationGroup::create();

        $user->reservations()->whereIn('id', $data->reservations_ids)->update(['reservation_group_id' => $reservationGroup->id]);

        $response = $this->reservationPaymentResponder->handle($reservationGroup, $user);

        return $response->toResponse();
    }
}
