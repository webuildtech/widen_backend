<?php

namespace App\Http\Controllers\User;

use App\Data\User\Reservations\StoreReservationData;
use App\Http\Controllers\Controller;
use App\Interfaces\Repositories\Models\ReservationRepositoryInterface;
use App\Services\ReservationSlotService;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    public function __construct(
        protected ReservationRepositoryInterface $reservationRepository,
        protected ReservationSlotService         $reservationSlotService,
    )
    {
    }

    public function store(StoreReservationData $data): JsonResponse
    {
        if (!$this->reservationSlotService->isAllAvailable($data->slots)) {
            return response()->json(['error' => 'Įvyko klaida, bandykite dar kartą!'], 424);
        }

        $slots = $this->reservationSlotService->isAllFree($data->slots);

        if (count($slots['occupy']) > 0) {
            return response()->json(['slots' => $slots['occupy']], 423);
        }

        $reservation = $this->reservationRepository->create($data, $slots['free']);

        return response()->json(['reservation' => $reservation], 201);
    }
}
