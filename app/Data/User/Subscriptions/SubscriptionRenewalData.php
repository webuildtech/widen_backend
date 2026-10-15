<?php

namespace App\Data\User\Subscriptions;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SubscriptionRenewalData extends Data
{
    public function __construct(
        public string $plan_name,

        public string $periodicity_type,

        public Carbon $expired_at,

        public float  $price,

        public float  $paid_from_balance,

        public float  $amount_to_pay,
    )
    {
    }
}
