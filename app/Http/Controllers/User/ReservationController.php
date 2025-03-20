<?php

namespace App\Http\Controllers\User;

use App\Data\User\Reservations\StoreReservationData;
use App\Http\Controllers\Controller;
use App\Interfaces\Repositories\Models\ReservationRepositoryInterface;
use App\Services\MakeCommerceService;
use App\Services\PaymentService;
use App\Services\ReservationSlotService;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    public function __construct(
        protected ReservationRepositoryInterface $reservationRepository,
        protected ReservationSlotService         $reservationSlotService,
        protected MakeCommerceService            $makeCommerceService,
        protected PaymentService                 $paymentService,
    )
    {
    }

    public function store(StoreReservationData $data): JsonResponse
    {
        if (!$this->reservationSlotService->isAllAvailable($data->slots)) {
            return response()->json(['error' => 'Įvyko klaida, bandykite dar kartą!'], 424);
        }

        $slots = $this->reservationSlotService->isAllFree($data->slots, auth()->guard('user')->user());

        if (count($slots['occupy']) > 0) {
            return response()->json(['slots' => $slots['occupy']], 423);
        }

        $reservation = $this->reservationRepository->create($data, $slots['free']);

        $payment = $this->paymentService->createFromReservation($reservation, auth()->guard('user')->user());

        if ($payment->paid_amount > 0) {
            $url = $this->makeCommerceService->createTransaction(
                $payment,
                $reservation->user_id ? $reservation->user->email : $reservation->guest_email,
                request()->ip()
            );

            return response()->json(['url' => $url], 201);
        }

        $payment = $this->paymentService->approve($payment);

        return response()->json(['balance' => $payment->user->balance]);
    }
}
