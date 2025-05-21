<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\ReservationTimes\IndexReservationTimeData;
use App\Data\Admin\ReservationTimes\SearchReservationTimeData;
use App\Enums\CourtType;
use App\Http\Controllers\Controller;
use App\Models\ReservationTime;

class ReservationTimeController extends Controller
{
    public function index(SearchReservationTimeData $data)
    {
        $reservationTimes = ReservationTime::with(['court', 'reservation', 'reservation.user'])
            ->whereCanceledAt(null)
            ->whereDate('start_time', '>=', $data->date_from)
            ->whereDate('end_time', '<=', $data->date_to);

        if (is_array($data->courts_ids)) {
            $reservationTimes->whereIn('court_id', $data->courts_ids);
        }

        if ($data->court_type instanceof CourtType) {
            $reservationTimes->whereHas('court', fn ($query) => $query->where('type', $data->court_type));
        }

        return IndexReservationTimeData::collect($reservationTimes->get());
    }
}
