<?php

namespace App\Console\Commands;

use App\Mail\ReservationUnpaidReminderMail;
use App\Models\Reservation;
use App\Repositories\ReservationRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ReservationUnpaidReminderCommand extends Command
{
    protected $signature = 'app:reservation-unpaid-reminder-command';

    public function __construct(
        protected ReservationRepository $reservationRepository,
    )
    {
        parent::__construct();
    }

    public function handle()
    {
        $daysBefore = [5, 4];

        $date = now()->setSeconds(0);

        foreach ($daysBefore as $days) {
            $this->reservationRepository->getUnpaidForDate($date->clone()->addDays($days))
                ->each(function (Reservation $reservation) use ($days) {
                    if ($reservation->owner->balance - $reservation->price_with_vat < -$reservation->owner->overdraft_limit) {
                        Mail::queue(new ReservationUnpaidReminderMail($reservation, $days));
                    }
                });
        }
    }
}
