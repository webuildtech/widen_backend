<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Reservations\ReservationBulkActionData;
use App\Data\Admin\Reservations\ReservationCalendarData;
use App\Data\Admin\Reservations\ReservationFilterData;
use App\Data\Admin\Reservations\MultiReservationStoreData;
use App\Data\Admin\Reservations\ReservationListData;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Reservations\MultiReservationService;
use App\Services\Reservations\ReservationService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Enums\FilterOperator;
use Spatie\QueryBuilder\QueryBuilder;

class ReservationController extends Controller
{
    public function __construct(
        protected MultiReservationService $multiReservationService,
        protected ReservationService      $reservationService,
    )
    {
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
                'refunded_amount',
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
                AllowedFilter::exact('court.court_type_id'),
                'is_paid',
                AllowedFilter::scope('start_time_from'),
                AllowedFilter::scope('end_time_to'),
                AllowedFilter::scope('paid_at_between'),
                AllowedFilter::scope('canceled_at_between'),
                AllowedFilter::scope('updated_at_between'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return ReservationListData::collect($reservations);
    }

    public function store(MultiReservationStoreData $data)
    {
        $result = $this->multiReservationService->store($data);

        if ($result->hasBlockedSlots() && !$data->force_create) {
            return response()->json(['blocked_slots' => $result->blockedSlots()], 403);
        }

        return $result->freeSlots();
    }

    public function calendar(ReservationFilterData $data)
    {
        $reservationTimes = Reservation::with(['court', 'owner'])
            ->orderBy('court_id')
            ->whereCanceledAt(null)
            ->whereDate('start_time', '>=', $data->date_from)
            ->whereDate('end_time', '<=', $data->date_to);

        if (is_array($data->courts_ids)) {
            $reservationTimes->whereIn('court_id', $data->courts_ids);
        }

        if (is_int($data->court_type_id)) {
            $reservationTimes->whereHas('court', fn($query) => $query->where('court_type_id', $data->court_type_id));
        }

        return ReservationCalendarData::collect($reservationTimes->get());
    }

    public function pay(Reservation $reservation): array
    {
        if (!$reservation->is_paid && !$reservation->canceled_at) {
            $reservation->update(['is_paid' => true, 'paid_at' => now(), 'payment_source' => 'admin']);

            $this->reservationService->refundSlots($reservation);
        }

        return [];
    }

    public function cancel(Reservation $reservation): array
    {
        $this->reservationService->cancel($reservation);

        return [];
    }

    public function cancelAllSame(Reservation $reservation): array
    {
        $quantity = $this->reservationService->cancelAllSame($reservation);

        return ['quantity' => $quantity];
    }

    public function destroy(Reservation $reservation): array
    {
        $this->reservationService->delete($reservation);

        return [];
    }

    public function bulkAction(ReservationBulkActionData $data)
    {
        $this->reservationService->bulkAction(
            Reservation::whereIn('id', $data->ids)->get(),
            $data->action
        );

        return [];
    }
}
