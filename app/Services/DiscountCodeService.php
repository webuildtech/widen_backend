<?php

namespace App\Services;

use App\Models\DiscountCode;
use Carbon\Carbon;

class DiscountCodeService
{
    public function validateCode(string $code): array
    {
        $discountCode = DiscountCode::whereCode($code)->first();

        if (!$discountCode) {
            return ['valid' => false, 'message' => 'Kodas nerastas.'];
        }

        if (!$discountCode->is_active) {
            return ['valid' => false, 'message' => 'Kodas neaktyvus.'];
        }

        $now = Carbon::now();

        if ($discountCode->date_from && $now->lt($discountCode->date_from)) {
            return ['valid' => false, 'message' => 'Kodas dar negalioja.'];
        }

        if ($discountCode->date_to && $now->gt($discountCode->date_to)) {
            return ['valid' => false, 'message' => 'Kodo galiojimo laikas pasibaigęs.'];
        }

        if ($discountCode->usage_limit !== null && $discountCode->used >= $discountCode->usage_limit) {
            return ['valid' => false, 'message' => 'Kodas jau buvo panaudotas maksimaliai.'];
        }

        return ['valid' => true, 'discountCode' => $discountCode];
    }
}
