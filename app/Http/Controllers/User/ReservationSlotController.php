<?php

namespace App\Http\Controllers\User;

use App\Data\User\Courts\CourtFilterData;
use App\Data\User\ReservationSlots\MyReservationSlotData;
use App\Http\Controllers\Controller;
use App\Models\ReservationSlot;

class ReservationSlotController extends Controller
{
    public function my(CourtFilterData $data)
    {
        $user = auth()->guard('user')->user();

        $slots =  ReservationSlot::query()
            ->active()
            ->whereDate('slot_start', $data->date)
            ->whereHas('reservation', function ($query) use ($user) {
                $query->where('owner_type', 'user')
                    ->where('owner_id', $user->id);
            })
            ->get();

        return MyReservationSlotData::collect($slots);
    }
}
