<?php

namespace App\Services;

use App\Data\Admin\DiscountCodes\DiscountCodeStoreData;
use App\Data\Admin\DiscountCodes\DiscountCodeUpdateData;
use App\Models\Court;
use App\Models\DiscountCode;
use App\Data\Core\DiscountCodes\DiscountLineData;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class DiscountCodeService
{
    public function create(DiscountCodeStoreData $data): DiscountCode
    {
        $discountCode = DiscountCode::create($data->except('court_types_ids', 'windows')->all());

        $this->syncCourtTypes($discountCode, $data->court_types_ids);
        $this->syncWindows($discountCode, $data->windows);

        return $discountCode->refresh();
    }

    public function update(DiscountCode $discountCode, DiscountCodeUpdateData $data): DiscountCode
    {
        $discountCode->update($data->except('court_types_ids', 'windows')->all());

        $this->syncCourtTypes($discountCode, $data->court_types_ids);
        $this->syncWindows($discountCode, $data->windows);

        return $discountCode->fresh();
    }

    public function lines(Collection $lines): array
    {
        $courtTypeIds = Court::whereIn('id', $lines->pluck('court_id')->filter()->unique())
            ->pluck('court_type_id', 'id');

        return $lines
            ->map(fn(array $line) => new DiscountLineData(
                $line['court_type_id'] ?? $courtTypeIds->get($line['court_id'] ?? null),
                $line['starts_at'] ?? null,
            ))
            ->all();
    }

    public function validateCode(string $code, ?array $lines = null): array
    {
        $discountCode = DiscountCode::with(['courtTypes', 'windows'])->whereCode($code)->first();

        if (!$discountCode) {
            return ['valid' => false, 'message' => __('discount_codes.not_found')];
        }

        if (!$discountCode->is_active) {
            return ['valid' => false, 'message' => __('discount_codes.inactive')];
        }

        $now = Carbon::now();

        if ($discountCode->date_from && $now->lt($discountCode->date_from)) {
            return ['valid' => false, 'message' => __('discount_codes.not_yet_valid')];
        }

        if ($discountCode->date_to && $now->gt($discountCode->date_to)) {
            return ['valid' => false, 'message' => __('discount_codes.expired')];
        }

        if ($discountCode->usage_limit !== null && $discountCode->used >= $discountCode->usage_limit) {
            return ['valid' => false, 'message' => __('discount_codes.usage_limit_reached')];
        }

        if ($lines !== null && ($message = $this->restrictionMessage($discountCode, $lines))) {
            return ['valid' => false, 'message' => $message];
        }

        return ['valid' => true, 'discountCode' => $discountCode];
    }

    private function restrictionMessage(DiscountCode $discountCode, array $lines): ?string
    {
        if (count($lines) === 0) {
            return match (true) {
                $discountCode->isLimitedToCourtTypes() => __('discount_codes.only_for_court_reservations'),
                $discountCode->isLimitedToWindows() => __('discount_codes.only_at_specific_times'),
                default => null,
            };
        }

        $byCourtType = collect($lines)
            ->filter(fn(DiscountLineData $line) => $discountCode->appliesToCourtType($line->court_type_id));

        if ($byCourtType->isEmpty()) {
            return __('discount_codes.not_valid_for_selected_courts');
        }

        if (!$byCourtType->contains(fn(DiscountLineData $line) => $discountCode->appliesToTime($line->starts_at))) {
            return __('discount_codes.not_valid_at_selected_times');
        }

        return null;
    }

    private function syncCourtTypes(DiscountCode $discountCode, $courtTypes): void
    {
        if (is_array($courtTypes)) {
            $discountCode->courtTypes()->sync($courtTypes);
        }
    }

    private function syncWindows(DiscountCode $discountCode, $windows): void
    {
        if (!$windows instanceof Collection) {
            return;
        }

        $discountCode->windows()->forceDelete();
        $discountCode->windows()->createMany($windows->map->toArray()->all());
    }
}
