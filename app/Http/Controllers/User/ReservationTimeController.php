<?php

namespace App\Http\Controllers\User;

use App\Data\User\ReservationTimes\IndexReservationTimeData;
use App\Data\User\ReservationTimes\ReservationTimeData;
use App\Http\Controllers\Controller;
use App\Models\ReservationTime;
use LucasDotVin\Soulbscription\Models\FeatureConsumption;

class ReservationTimeController extends Controller
{
    public function index(IndexReservationTimeData $data)
    {
        $reservationTimes = ReservationTime::with(['court'])
            ->whereHas('reservation', fn($query) => $query->where('user_id', auth()->user()->id)->where('is_paid', true));

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
        $user = auth()->user();

        if ($reservationTime->canceled_at || $user->id !== $reservationTime->reservation->user_id) {
            return response()->json(['error' => 'Veiksmas negalimas!'], 406);
        }

        if ($reservationTime->start_time->isBefore(now()->addHours($user->cancel_before))) {
            $reservationTime->update(['canceled_at' => now()]);
            $reservationTime->slots()->update(['try_sell' => true]);
        } else {
            $user->addBalance($reservationTime->price_with_vat);

            $reservationTime->update(['refunded_amount' => $reservationTime->price_with_vat, 'canceled_at' => now()]);

            $reservationTime->slots()->delete();
        }

        return response()->json(['balance' => $user->balance]);
    }
}
