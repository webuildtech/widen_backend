<?php

namespace App\Http\Controllers\User;

use App\Data\User\DiscountCodes\DiscountCodeCheckData;
use App\Data\User\DiscountCodes\DiscountCodeData;
use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Services\DiscountCodeService;
use Illuminate\Http\JsonResponse;

class DiscountCodeController extends Controller
{
    public function __construct(
        protected DiscountCodeService $discountCodeService,
    )
    {
    }

    public function check(DiscountCodeCheckData $data): JsonResponse|DiscountCodeData
    {
        $result = $this->discountCodeService->validateCode(
            $data->code,
            match (true) {
                $data->court_type_ids !== null => $data->court_type_ids,
                $data->court_ids !== null => Court::whereIn('id', $data->court_ids)->pluck('court_type_id')->all(),
                default => null,
            }
        );

        return $result['valid']
            ? DiscountCodeData::from($result['discountCode'])
            : response()->json(['code' => $result['message']], 406);
    }
}
