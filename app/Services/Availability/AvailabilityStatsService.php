<?php

namespace App\Services\Availability;

use App\Enums\AvailabilitySlotType;
use App\Models\AvailabilitySlot;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AvailabilityStatsService
{
    public function getStatsByIntervalCourtType(
        CarbonImmutable|Carbon $startDate,
        CarbonImmutable|Carbon $endDate = null,
        int                    $courtTypeId = null,
        string                 $timeFrom = null,
        string                 $timeTo = null
    ): array
    {
        $start = $startDate->toDateString();
        $end = $endDate?->toDateString();

        if ($end && $end < $start) {
            [$start, $end] = [$end, $start];
        }

        if ($timeFrom && $timeTo && $timeTo < $timeFrom) {
            [$timeFrom, $timeTo] = [$timeTo, $timeFrom];
        }

        $query = AvailabilitySlot::query()
            ->when(
                $end,
                fn($q) => $q->whereBetween('date', [$start, $end]),
                fn($q) => $q->whereDate('date', $start)
            )
            ->when($timeFrom, fn($q) => $q->where('start_time', '>=', $timeFrom))
            ->when($timeTo, fn($q) => $q->where('end_time', '<=', $timeTo))
            ->select([
                'court_type_id',
                DB::raw('COUNT(*) AS total'),
                DB::raw('SUM(CASE WHEN COALESCE(is_reserved,0)=1 THEN 1 ELSE 0 END) AS reserved'),
                DB::raw('SUM(CASE WHEN COALESCE(is_blocked,0)=1 THEN 1 ELSE 0 END) AS blocked'),
                DB::raw('SUM(CASE WHEN COALESCE(is_reserved,0)=0 AND COALESCE(is_blocked,0)=0 THEN 1 ELSE 0 END) AS free'),
                ...$this->reservedByTypeSelects(),
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
            'reserved_by_type' => $this->sumReservedByType($stats),
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
                    'reserved_by_type' => $this->extractReservedByTypeFromRow($row),
                ]),
            ];
        })->toArray();

        return [
            ...$result,
            'by_court_type' => $byType,
        ];
    }

    private function reservedByTypeSelects(): array
    {
        return array_map(function (AvailabilitySlotType $type) {
            $alias = $this->reservedByTypeAlias($type);

            return DB::raw(sprintf(
                "SUM(CASE WHEN COALESCE(is_reserved,0)=1 AND type='%s' THEN 1 ELSE 0 END) AS %s",
                $type->value,
                $alias
            ));
        }, AvailabilitySlotType::cases());
    }

    private function sumReservedByType(Collection $stats): array
    {
        $result = [];

        foreach (AvailabilitySlotType::cases() as $type) {
            $result[$type->value] = $stats->sum($this->reservedByTypeAlias($type));
        }

        return $result;
    }

    private function extractReservedByTypeFromRow(object $row): array
    {
        $result = [];

        foreach (AvailabilitySlotType::cases() as $type) {
            $alias = $this->reservedByTypeAlias($type);

            $result[$type->value] = $row->{$alias} ?? 0;
        }

        return $result;
    }

    private function reservedByTypeAlias(AvailabilitySlotType $type): string
    {
        return 'reserved_type_' . Str::snake($type->value);
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

        $reservedByType = [];

        foreach (AvailabilitySlotType::cases() as $type) {
            $count = $stats['reserved_by_type'][$type->value] ?? 0;

            $reservedByType[] = [
                'type' => $type,
                'count' => $count,
                'pct' => $this->pct($count, $total),
            ];
        }

        $stats['reserved_by_type'] = $reservedByType;

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
