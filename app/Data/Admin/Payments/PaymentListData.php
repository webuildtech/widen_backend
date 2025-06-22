<?php

namespace App\Data\Admin\Payments;

use App\Data\Core\Owners\OwnerData;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PaymentListData extends Data
{
    public function __construct(
        public int       $id,

        public string    $owner_type,

        public OwnerData $owner,

        public ?string   $paymentable_type,

        public float     $price,

        public float     $discount,

        public float     $vat,

        public float     $price_with_vat,

        public float     $paid_amount_from_balance,

        public float     $paid_amount,

        public Carbon    $paid_at,

        public Carbon    $updated_at,
    )
    {
    }
}
