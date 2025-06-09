<?php

namespace App\Data\User\Payments;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PaymentListData extends Data
{
    public function __construct(
        public int     $id,

        public ?string $paymentable_type,

        public float   $paid_amount_from_balance,

        public float   $paid_amount,

        public float   $price_with_vat,

        public Carbon  $paid_at,
    )
    {
    }
}
