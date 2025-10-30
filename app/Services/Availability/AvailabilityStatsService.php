<?php

namespace App\Services\Availability;

use App\Models\AvailabilitySlot;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class AvailabilityStatsService
{
    public function getStatsByIntervalCourtType(
        CarbonImmutable|Carbon $startDate,
        CarbonImmutable|Carbon $endDate = null,
        int                    $courtTypeId = null
    ): array
    {
        $start = $startDate->toDateString();
        $end = $endDate?->toDateString();

        if ($end && $end < $start) {
            [$start, $end] = [$end, $start];
        }

        $query = AvailabilitySlot::query()
            ->when($end,
                fn($q) => $q->whereBetween('date', [$start, $end]),
                fn($q) => $q->whereDate('date', $start)
            )
            ->select([
                'court_type_id',
                DB::raw('COUNT(*) AS total'),
                DB::raw('SUM(CASE WHEN COALESCE(is_reserved,0)=1 THEN 1 ELSE 0 END) AS reserved'),
                DB::raw('SUM(CASE WHEN COALESCE(is_blocked,0)=1 THEN 1 ELSE 0 END) AS blocked'),
                DB::raw('SUM(CASE WHEN COALESCE(is_reserved,0)=0 AND COALESCE(is_blocked,0)=0 THEN 1 ELSE 0 END) AS free'),
            ])
            ->groupBy('court_type_id')
            ->orderBy('court_type_id');

        if ($courtTypeId) {
            $query->where('court_type_id', $courtTypeId);
        }

        $stats = $query->get();

        $overall = [
            'total' => $stats->sum('total'),
            'reserved' => $stats->sum('reserved'),
            'blocked' => $stats->sum('blocked'),
            'free' => $stats->sum('free'),
        ];

        $result = ['overall' => $this->enrichPeriod($overall)];

        if (!is_null($courtTypeId)) {
            return $result;
        }

        $byType = $stats->mapWithKeys(function ($row) {
            return [
                $row->court_type_id => $this->enrichPeriod([
                    'total' => $row->total,
                    'reserved' => $row->reserved,
                    'blocked' => $row->blocked,
                    'free' => $row->free,
                ]),
            ];
        })->toArray();

        return [
            ...$result,
            'by_court_type' => $byType,
        ];
    }

    private function enrichPeriod(array $stats): array
    {
        $total = $stats['total'];

        $occupied = $stats['reserved'] + $stats['blocked'];
        $stats['occupied'] = $occupied;

        $stats['occupied_pct'] = $this->pct($occupied, $total);
        $stats['reserved_pct'] = $this->pct($stats['reserved'], $total);
        $stats['blocked_pct'] = $this->pct($stats['blocked'], $total);
        $stats['free_pct'] = $this->pct($stats['free'], $total);

        return $stats;
    }

    private function pct(int $part, int $total): float
    {
        if (!$total) {
            return 0.00;
        }

        return round(($part / $total) * 100, 2);
    }
}
