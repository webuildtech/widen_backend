<?php

namespace App\Services\Payments;

use App\Data\Admin\Dashboard\IncomeFilterData;
use App\Models\CourtType;
use App\Models\Payment;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelData\Optional;

class IncomeService
{
    public function getIncomes(): array
    {
        return $this->collectIncomes();
    }

    public function getIncomesByInterval(IncomeFilterData $data): array
    {
        return $this->collectIncomes($data);
    }

    protected function collectIncomes(?IncomeFilterData $interval = null): array
    {
        $sources = CourtType::all()->map(fn (CourtType $courtType) => [
            'label' => $courtType->name,
            'query' => Reservation::whereIsPaid(true)->whereHas('court', fn ($q) => $q->where('court_type_id', $courtType->id)),
            'has_refunds' => true,
        ]);

        $sources->push([
            'label' => 'Prenumeratos',
            'query' => Payment::whereStatus('paid')->wherePaymentableType('plan'),
            'has_refunds' => false,
        ]);

        return $sources->map(function ($source) use ($interval) {
            $query = clone $source['query'];

            if ($interval) {
                $query = $this->applyDateFilter($query, $interval->date_from, $interval->date_to instanceof Optional ? null : $interval->date_to);
                $data = $this->calculateIntervalIncome($query, $source['has_refunds']);
            } else {
                $data = $this->calculatePeriodIncome($query, $source['has_refunds']);
            }

            return [
                'source' => $source['label'],
                ...$data,
            ];
        })->toArray();
    }

    protected function applyDateFilter(Builder $query, Carbon $from, ?Carbon $to = null): Builder
    {
        $query->whereDate('paid_at', '>=', $from);

        if ($to) {
            $query->whereDate('paid_at', '<=', $to);
        }

        return $query;
    }

    protected function calculatePeriodIncome(Builder $query, bool $hasRefunds): array
    {
        $periods = [
            'today' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'last_7_days' => now()->subDays(7),
            'month' => now()->startOfMonth(),
            'last_30_days' => now()->subDays(30),
            'year' => now()->startOfYear(),
        ];

        $results = [];

        foreach ($periods as $key => $from) {
            $filtered = $this->applyDateFilter(clone $query, $from);
            $results[$key] = $this->sumWithRefunds($filtered, $hasRefunds);
        }

        $results['total'] = $this->sumWithRefunds($query, $hasRefunds);

        return $results;
    }

    protected function calculateIntervalIncome(Builder $query, bool $hasRefunds): array
    {
        return [
            'income' => $this->sumWithRefunds($query, $hasRefunds),
        ];
    }

    protected function sumWithRefunds(Builder $query, bool $hasRefunds): float
    {
        $sum = $query->sum('price_with_vat');

        if ($hasRefunds) {
            $sum -= $query->sum('refunded_amount');
        }

        return $sum;
    }
}
