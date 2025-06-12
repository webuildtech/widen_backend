<?php

namespace App\Console\Commands;

use App\Models\Plan;
use App\Models\PlanCourtTypeRule;
use App\Models\Reservation;
use App\Models\ReservationGroup;
use App\Repositories\ReservationRepository;
use App\Services\Payments\PaymentService;
use App\Services\PlanCourtTypeRuleService;
use Illuminate\Console\Command;

class ReservationDeleteCommand extends Command
{
    protected $signature = 'app:reservation-delete-command';

    public function __construct(
        protected ReservationRepository    $reservationRepository,
        protected PaymentService           $paymentService,
        protected PlanCourtTypeRuleService $planCourtTypeRuleService
    )
    {
        parent::__construct();
    }

    public function handle()
    {
        $cancelHoursBefore = PlanCourtTypeRule::max('cancel_hours_before');

        $this->reservationRepository->getUnpaidForDate(now()->addHours($cancelHoursBefore), '<=')
            ->each(function (Reservation $reservation) {
                $cancelBefore = now()->addHours(
                    $this->planCourtTypeRuleService->getCancelHoursBefore(
                        $reservation->owner_type === 'user' ? $reservation->owner : null,
                        $reservation->court->court_type_id
                    )
                );

                if ($cancelBefore >= $reservation->start_time) {
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
