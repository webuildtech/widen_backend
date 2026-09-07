<?php

namespace App\Http\Controllers\User;

use App\Data\User\DiscountCodes\DiscountCodeCheckData;
use App\Data\User\DiscountCodes\DiscountCodeCheckLineData;
use App\Data\User\DiscountCodes\DiscountCodeData;
use App\Http\Controllers\Controller;
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
            $data->lines === null ? null : $this->discountCodeService->lines(
                $data->lines->map(fn(DiscountCodeCheckLineData $line) => [
                    'court_id' => $line->court_id,
                    'court_type_id' => $line->court_type_id,
                    'starts_at' => $line->starts_at,
                ])
            )
        );

        return $result['valid']
            ? DiscountCodeData::from($result['discountCode'])
            : response()->json(['code' => $result['message']], 406);
    }
}
