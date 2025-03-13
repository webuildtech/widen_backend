<?php

namespace App\Http\Controllers\User;

use App\Data\User\ReservationTimes\IndexReservationTimeData;
use App\Data\User\ReservationTimes\ReservationTimeData;
use App\Http\Controllers\Controller;
use App\Models\ReservationTime;

class ReservationTimeController extends Controller
{
    public function index(IndexReservationTimeData $data)
    {
        $reservationTimes = ReservationTime::with(['court'])
            ->whereHas('reservation', fn($query) => $query->where('user_id', auth()->user()->id));

        switch ($data->type) {
            case 'active':
                $reservationTimes->where('end_time', '>', now())
                    ->whereNull('canceled_at')
                    ->orderBy('start_time');
                break;
            case 'past':
                $reservationTimes->with('slots')
                    ->where('end_time', '<=', now())
                    ->whereNull('canceled_at')
                    ->orderBy('end_time', 'desc');
                break;
            case 'cancelled':
                $reservationTimes->whereNotNull('canceled_at')
                    ->orderBy('canceled_at', 'desc');
        }

        return ReservationTimeData::collect($reservationTimes->get());
    }

    public function cancel(ReservationTime $reservationTime)
    {
        if ($reservationTime->canceled_at) {
            return response()->json(['error' => 'Veiksmas negalimas!'], 406);
        }

        $user = auth()->user();

        if ($reservationTime->start_time->isBefore(now()->addHours($user->cancel_before))) {
            $reservationTime->update(['canceled_at' => now()]);
            $reservationTime->slots()->update(['try_sell' => true]);
        } else {
            $user->update(['balance' => $user->balance + $reservationTime->price]);

            $reservationTime->update([
                'refunded_amount' => $reservationTime->price,
                'canceled_at' => now(),
            ]);

            $reservationTime->slots()->delete();
        }

        return response()->json(['balance' => $user->balance]);
    }
}
