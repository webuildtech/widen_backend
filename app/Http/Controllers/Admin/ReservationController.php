<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Reservations\CalendarReservationData;
use App\Data\Admin\Reservations\IndexReservationTimeData;
use App\Data\Admin\Reservations\SearchReservationTimeData;
use App\Data\Admin\Reservations\StoreMultiReservationData;
use App\Enums\CourtType;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Reservations\MultiReservationService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Enums\FilterOperator;
use Spatie\QueryBuilder\QueryBuilder;

class ReservationController extends Controller
{
    public function __construct(
        protected MultiReservationService $multiReservationService,
    ) {
    }

    public function index()
    {
        $reservations = QueryBuilder::for(Reservation::class)
            ->with('owner', 'court')
            ->defaultSort('-start_time')
            ->allowedSorts([
                'start_time',
                'end_time',
                'court_id',
                'price_with_vat',
                'owner_type',
                'is_paid',
                'paid_at',
                'canceled_at',
                'updated_at'
            ])
            ->allowedFilters([
                'owner.first_name',
                'owner.last_name',
                'owner.email',
                'owner.phone',
                AllowedFilter::operator('price_from', FilterOperator::GREATER_THAN_OR_EQUAL, 'and', 'price_with_vat'),
                AllowedFilter::operator('price_to', FilterOperator::LESS_THAN_OR_EQUAL, 'and', 'price_with_vat'),
                AllowedFilter::exact('court_id'),
                'court.type',
                'is_paid',
                AllowedFilter::scope('paid_at_between'),
                AllowedFilter::scope('canceled_at_between'),
                AllowedFilter::scope('updated_at_between'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return IndexReservationTimeData::collect($reservations);
    }

    public function store(StoreMultiReservationData $data)
    {
        $result = $this->multiReservationService->store($data);

        if ($result->hasBlockedSlots() && !$data->force_create) {
            return response()->json(['blocked_slots' => $result->blockedSlots()], 403);
        }

        return $result->freeSlots();
    }

    public function calendar(SearchReservationTimeData $data)
    {
        $reservationTimes = Reservation::with(['court', 'owner'])
            ->whereCanceledAt(null)
            ->whereDate('start_time', '>=', $data->date_from)
            ->whereDate('end_time', '<=', $data->date_to);

        if (is_array($data->courts_ids)) {
            $reservationTimes->whereIn('court_id', $data->courts_ids);
        }

        if ($data->court_type instanceof CourtType) {
            $reservationTimes->whereHas('court', fn($query) => $query->where('type', $data->court_type));
        }

        return CalendarReservationData::collect($reservationTimes->get());
    }

    public function destroy(Reservation $reservation): array
    {
        if ($reservation->is_paid && $reservation->owner instanceof User) {
            $reservation->owner->addBalance($reservation->price_with_vat);
        }

        $reservation->slots()->delete();
        $reservation->delete();

        return [];
    }
}
