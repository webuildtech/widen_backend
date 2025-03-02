<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Models\Reservation;
use App\Models\ReservationSlot;
use App\Services\IntervalTimesService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function store()
    {
        $times = request()->get('slots');

        if (is_array($times) && count($times) > 0) {
            $court = Court::first();
            $intervalTimesService = new IntervalTimesService();

            foreach ($times as $key => $time) {
                $startTime = Carbon::parse($time['date'] . ' ' . $time['start_time']);
                $endTime = Carbon::parse($time['date'] . ' ' . $time['end_time']);

                $interval = $court->intervals()
                    ->whereDate('date_from', '<=', $startTime)
                    ->whereDate('date_to', '>=', $startTime)
                    ->first();

                $availableSlots = collect($intervalTimesService->getIntervalTimes($interval, $startTime));

                $requestedSlots = [];
                $currentTime = $startTime->copy();

                while ($currentTime < $endTime) {
                    $requestedSlots[] = [
                        "start_time" => $currentTime->format('H:i'),
                        "end_time" => $currentTime->copy()->addMinutes(30)->format('H:i')
                    ];
                    $currentTime->addMinutes(30);
                }

                foreach ($requestedSlots as $slot) {
                    $availableSlot = $availableSlots->filter(fn ($available) =>
                        $available['start_time'] === $slot['start_time'] &&
                        $available['end_time'] === $slot['end_time']
                    )->first();

                    if ($availableSlot) {
                        $times[$key]['slots'][] = $availableSlot;
                    } else {
                        \Log::info('Sloto nera!');
                        return false; // Slotas net neegzistuoja!
                    }
                }
            }

            $reservation = Reservation::create();

            foreach ($times as $time) {
                $startTime = Carbon::parse($time['date'] . ' ' . $time['start_time']);
                $endTime = Carbon::parse($time['date'] . ' ' . $time['end_time']);

                $reservationTime = $reservation->times()->create([
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'price' => 0
                ]);

                foreach ($time['slots'] as $slot) {
                    ReservationSlot::create([
                        'reservation_id' => $reservation->id,
                        'reservation_time_id' => $reservationTime->id,
                        'slot_start' => Carbon::parse($time['date'] . ' ' . $slot['start_time']),
                        'slot_end' => Carbon::parse($time['date'] . ' ' . $slot['end_time']),
                        'price' => $slot['price'],
                    ]);

                    $reservationTime->price += $slot['price'];
                    $reservation->total_price += $slot['price'];
                }

                $reservationTime->save();
            }

            $reservation->save();
        }
    }
}
