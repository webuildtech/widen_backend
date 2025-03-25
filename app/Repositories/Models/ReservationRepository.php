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

    public function create(Data $data, array $slots = [], int $useFreeSlots = 0, float $discount = 0): Reservation
    {
        $reservation = Reservation::create([
            'guest_first_name' => $data->guest_first_name,
            'guest_last_name' => $data->guest_last_name,
            'guest_email' => $data->guest_email,
            'guest_phone' => $data->guest_phone,
            'user_id' => $data->user_id,
        ]);

        $mergedSlots = $this->mergeSlots($slots);

        foreach ($mergedSlots as $mergedSlot) {
            $reservationTime = $reservation->times()->create([
                'start_time' => Carbon::parse($mergedSlot['date'] . ' ' . $mergedSlot['start_time']),
                'end_time' => Carbon::parse($mergedSlot['date'] . ' ' . $mergedSlot['end_time']),
                'court_id' => $mergedSlot['court_id'],
            ]);

            foreach ($mergedSlot['related'] as $slot) {
                $isFreeFromPlan = false;

                if ($useFreeSlots > 0) {
                    $isFreeFromPlan = true;
                    $reservationTime->used_free_slots += 1;
                    $useFreeSlots--;
                }

                $priceDetails = applyDiscountAndCalculatePriceDetails($slot['price'], $discount);

                $reservationTime->slots()->create([
                    'reservation_id' => $reservation->id,
                    'slot_start' => Carbon::parse($slot['date'] . ' ' . $slot['start_time']),
                    'slot_end' => Carbon::parse($slot['date'] . ' ' . $slot['end_time']),
                    'court_id' => $slot['court_id'],
                    'price' => $isFreeFromPlan ? 0 : $priceDetails->priceWithVat,
                    'discount' => $priceDetails->discount,
                    'is_free_from_plan' => $isFreeFromPlan
                ]);

                $reservationTime->price += $isFreeFromPlan ? 0 : $priceDetails->priceWithVat;
                $reservationTime->discount += $priceDetails->discount;
                $reservation->vat += $priceDetails->vat;
            }

            $reservationTime->save();
            $reservation->price += $reservationTime->price;
            $reservation->discount += $reservationTime->discount;
        }

        $reservation->save();

        return $reservation->refresh();
    }

    private function mergeSlots(array $slots): array
    {
        $mergedSlots = [];

        collect($slots)->sortBy([['date', 'asc'], ['court_id', 'asc'], ['start_time', 'asc']])
            ->each(function ($slot) use (&$mergedSlots) {
                $found = false;

                foreach ($mergedSlots as &$mSlot) {
                    if ($mSlot['date'] === $slot['date'] && $mSlot['court_id'] === $slot['court_id'] && $mSlot['end_time'] === $slot['start_time']) {
                        $mSlot['end_time'] = $slot['end_time'];
                        $mSlot['related'][] = $slot;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $slot['related'] = [$slot];
                    $mergedSlots[] = $slot;
                }
            });

        return $mergedSlots;
    }
}
