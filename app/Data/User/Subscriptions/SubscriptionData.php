<?php

namespace App\Data\User\Subscriptions;

use App\Data\User\Plans\PlanData;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SubscriptionData extends Data
{
    public function __construct(
        public Carbon   $started_at,

        public Carbon   $expired_at,

        public ?Carbon  $cancelled_at,

        public PlanData $plan,
    )
    {
    }
}
