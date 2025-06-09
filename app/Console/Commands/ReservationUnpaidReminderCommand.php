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

        foreach ($daysBefore as $days) {
            $this->reservationRepository->getUnpaidForDate(now()->addDays($days)->setMinutes(0)->setSeconds(0))
                ->each(function (Reservation $reservation) use ($days) {
                    if ($reservation->owner->balance - $reservation->price_with_vat < -100) {
                        Mail::queue(new ReservationUnpaidReminderMail($reservation, $days));
                    }
                });
        }
    }
}
