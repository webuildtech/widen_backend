<?php

namespace App\Data\User\Subscriptions;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SubscriptionData extends Data
{
    public function __construct(
        public int     $plan_id,

        public Carbon  $started_at,

        public Carbon  $expired_at,

        public ?Carbon $cancelled_at,
    )
    {
    }
}
