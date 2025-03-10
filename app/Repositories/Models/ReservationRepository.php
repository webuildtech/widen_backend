<?php

namespace App\Repositories\Models;

use App\Interfaces\Repositories\Models\ReservationRepositoryInterface;
use App\Models\Reservation;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class ReservationRepository extends BaseRepository implements ReservationRepositoryInterface
{
    public function __construct(Reservation $model)
    {
        parent::__construct($model);
    }

    public function create(Data $data, array $timeSlots = []): Reservation
    {
        $reservation = Reservation::create([
            'guest_first_name' => $data->guest_first_name,
            'guest_last_name' => $data->guest_last_name,
            'guest_email' => $data->guest_email,
            'guest_phone' => $data->guest_phone,
            'user_id' => $data->user_id,
        ]);

        foreach ($timeSlots as $timeSlot) {
            $reservationTime = $reservation->times()->create([
                'start_time' => Carbon::parse($timeSlot['date'] . ' ' . $timeSlot['start_time']),
                'end_time' => Carbon::parse($timeSlot['date'] . ' ' . $timeSlot['end_time']),
                'court_id' => $timeSlot['court_id'],
            ]);

            foreach ($timeSlot['slots'] as $slot) {
                $reservationTime->slots()->create([
                    'reservation_id' => $reservation->id,
                    'slot_start' => Carbon::parse($slot['date'] . ' ' . $slot['start_time']),
                    'slot_end' => Carbon::parse($slot['date'] . ' ' . $slot['end_time']),
                    'court_id' => $slot['court_id'],
                    'price' => $slot['price'],
                ]);

                $reservationTime->price += $slot['price'];
                $reservation->price += $slot['price'];
            }

            $reservationTime->save();
        }

        $reservation->vat = round($reservation->price - ($reservation->price / 1.21), 2);
        $reservation->save();

        return $reservation->refresh();
    }
}
