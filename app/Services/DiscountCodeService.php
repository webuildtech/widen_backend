<?php

namespace App\Services;

use App\Data\Admin\DiscountCodes\DiscountCodeStoreData;
use App\Data\Admin\DiscountCodes\DiscountCodeUpdateData;
use App\Models\DiscountCode;
use Carbon\Carbon;

class DiscountCodeService
{
    public function create(DiscountCodeStoreData $data): DiscountCode
    {
        $discountCode = DiscountCode::create($data->except('court_types_ids')->all());

        $this->syncCourtTypes($discountCode, $data->court_types_ids);

        return $discountCode->refresh();
    }

    public function update(DiscountCode $discountCode, DiscountCodeUpdateData $data): DiscountCode
    {
        $discountCode->update($data->except('court_types_ids')->all());

        $this->syncCourtTypes($discountCode, $data->court_types_ids);

        return $discountCode->fresh();
    }

    /**
     * @param array<int>|null $courtTypeIds Court types the code is used for. An empty array means the code
     *                                      is not used for a reservation, null means the court types are unknown.
     */
    public function validateCode(string $code, ?array $courtTypeIds = null): array
    {
        $discountCode = DiscountCode::whereCode($code)->first();

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

        if ($courtTypeIds !== null && !$discountCode->appliesToAnyCourtType($courtTypeIds)) {
            return [
                'valid' => false,
                'message' => count($courtTypeIds) === 0
                    ? __('discount_codes.only_for_court_reservations')
                    : __('discount_codes.not_valid_for_selected_courts'),
            ];
        }

        return ['valid' => true, 'discountCode' => $discountCode];
    }

    private function syncCourtTypes(DiscountCode $discountCode, $courtTypes): void
    {
        if (is_array($courtTypes)) {
            $discountCode->courtTypes()->sync($courtTypes);
        }
    }
}
