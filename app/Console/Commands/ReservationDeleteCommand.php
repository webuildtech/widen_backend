<?php

namespace App\Console\Commands;

use App\Models\Plan;
use App\Models\Reservation;
use App\Models\ReservationGroup;
use App\Repositories\ReservationRepository;
use App\Services\Payments\PaymentService;
use Illuminate\Console\Command;

class ReservationDeleteCommand extends Command
{
    protected $signature = 'app:reservation-delete-command';

    public function __construct(
        protected ReservationRepository $reservationRepository,
        protected PaymentService $paymentService,
    )
    {
        parent::__construct();
    }

    public function handle()
    {
        $cancelBefore = max(Plan::max('cancel_before'), 48);

        $this->reservationRepository->getUnpaidForDate(now()->addHours($cancelBefore), '<=')
            ->each(function (Reservation $reservation) {
                if (now()->addHours($reservation->owner->cancel_before) >= $reservation->start_time) {
                    if ($reservation->owner->balance - $reservation->price_with_vat < -100) {
                        $reservation->slots()->delete();
                        $reservation->delete();
                    } else {
                        $reservationGroup = ReservationGroup::create();
                        $reservation->update(['reservation_group_id' => $reservationGroup->id]);

                        $payment = $this->paymentService->createFromReservationGroup($reservationGroup, $reservation->owner, true);
                        $this->paymentService->approve($payment);
                    }
                }
            });
    }
}
