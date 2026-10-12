<?php

namespace App\Models\Concerns;

use App\Enums\GameParticipantStatus;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait HasDiscountCodeScopes
{
    public function scopeWithPurchaseTotals(Builder $query): Builder
    {
        $lines = $this->reservationSlotLines()
            ->unionAll($this->gameSpotLines())
            ->unionAll($this->planLines());

        $totals = DB::query()
            ->fromSub($lines, 'purchase_lines')
            ->select('discount_code_id')
            ->selectRaw('sum(full_price) as purchases_value')
            ->selectRaw('sum(code_discount) as purchases_discount')
            ->groupBy('discount_code_id');

        return $query
            ->leftJoinSub($totals, 'purchase_totals', 'purchase_totals.discount_code_id', '=', 'discount_codes.id')
            ->select('discount_codes.*')
            ->selectRaw('coalesce(purchase_totals.purchases_value, 0) as purchases_value')
            ->selectRaw('coalesce(purchase_totals.purchases_discount, 0) as purchases_discount');
    }

    private function reservationSlotLines(): Builder
    {
        return $this->paidLines('reservation_slots')
            ->where('payments.paymentable_type', 'reservationGroup')
            ->join('reservations', 'reservations.reservation_group_id', '=', 'payments.paymentable_id')
            ->join('reservation_slots', 'reservation_slots.reservation_id', '=', 'reservations.id')
            ->whereNull('reservations.deleted_at')
            ->whereNull('reservation_slots.deleted_at')
            ->where('reservation_slots.is_refunded', false)
            ->having('code_discount', '>', 0);
    }

    private function gameSpotLines(): Builder
    {
        return $this->paidLines('game_participants')
            ->where('payments.paymentable_type', 'game')
            ->join('game_participants', 'game_participants.payment_id', '=', 'payments.id')
            ->whereNull('game_participants.deleted_at')
            ->where('game_participants.status', '!=', GameParticipantStatus::CANCELED);
    }

    private function planLines(): Builder
    {
        return $this->paidLines('payments')
            ->where('payments.paymentable_type', 'planPrice');
    }

    private function paidLines(string $line): Builder
    {
        $fullPrice = "$line.price_with_vat + $line.discount";
        $personalDiscount = "round(($fullPrice) * coalesce(users.discount_on_everything, 0) / 100, 2)";

        return Payment::query()
            ->leftJoin('users', fn($join) => $join
                ->on('users.id', '=', 'payments.owner_id')
                ->where('payments.owner_type', 'user'))
            ->where('payments.status', PaymentStatus::PAID)
            ->whereNotNull('payments.discount_code_id')
            ->select('payments.discount_code_id')
            ->selectRaw("$fullPrice as full_price")
            ->selectRaw("$line.discount - $personalDiscount as code_discount");
    }
}
