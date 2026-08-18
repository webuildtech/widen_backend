<?php

namespace App\Services\Payments;

use App\Data\Admin\Dashboard\IncomeFilterData;
use App\Models\CourtType;
use App\Models\Game;
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
            'queries' => [
                Reservation::whereIsPaid(true)->whereHas('court', fn ($q) => $q->where('court_type_id', $courtType->id)),
                Payment::whereStatus('paid')
                    ->wherePaymentableType('game')
                    ->whereHasMorph('paymentable', Game::class, fn ($q) => $q->where('court_type_id', $courtType->id)),
            ],
            'has_refunds' => true,
        ]);

        $sources->push([
            'label' => 'Prenumeratos',
            'queries' => [Payment::whereStatus('paid')->wherePaymentableType('planPrice')],
            'has_refunds' => false,
        ]);

        return $sources->map(function ($source) use ($interval) {
            $queries = array_map(fn (Builder $query) => clone $query, $source['queries']);

            if ($interval) {
                $queries = array_map(
                    fn (Builder $query) => $this->applyDateFilter($query, $interval->date_from, $interval->date_to instanceof Optional ? null : $interval->date_to),
                    $queries
                );

                $data = $this->calculateIntervalIncome($queries, $source['has_refunds']);
            } else {
                $data = $this->calculatePeriodIncome($queries, $source['has_refunds']);
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

    /**
     * @param array<int, Builder> $queries
     */
    protected function calculatePeriodIncome(array $queries, bool $hasRefunds): array
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
            $filtered = array_map(fn (Builder $query) => $this->applyDateFilter(clone $query, $from), $queries);

            $results[$key] = $this->sumWithRefunds($filtered, $hasRefunds);
        }

        $results['total'] = $this->sumWithRefunds($queries, $hasRefunds);

        return $results;
    }

    /**
     * @param array<int, Builder> $queries
     */
    protected function calculateIntervalIncome(array $queries, bool $hasRefunds): array
    {
        return [
            'income' => $this->sumWithRefunds($queries, $hasRefunds),
        ];
    }

    /**
     * @param array<int, Builder> $queries
     */
    protected function sumWithRefunds(array $queries, bool $hasRefunds): float
    {
        return collect($queries)->sum(function (Builder $query) use ($hasRefunds) {
            $sum = $query->sum('price_with_vat');

            if ($hasRefunds) {
                $sum -= $query->sum('refunded_amount');
            }

            return $sum;
        });
    }
}
