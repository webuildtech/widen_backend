<?php

namespace App\Http\Controllers\User;

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

    public function check(): JsonResponse|DiscountCodeData
    {
        $result = $this->discountCodeService->validateCode(request()->input('code'));

        return $result['valid']
            ? DiscountCodeData::from($result['discountCode'])
            : response()->json(['code' => $result['message']], 406);
    }
}
