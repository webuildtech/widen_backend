<?php

namespace App\Data\Admin\Subscriptions;

use App\Data\Admin\Plans\PlanSelectOptionData;
use App\Data\Admin\Users\UserSelectOptionData;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\LoadRelation;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SubscriptionListData extends Data
{
    public function __construct(
        public int                   $id,

        #[LoadRelation]
        public ?PlanSelectOptionData $plan,

        #[LoadRelation]
        public ?UserSelectOptionData $subscriber,

        public Carbon                $started_at,

        public Carbon                $expired_at,

        public bool                  $is_overdue,
    )
    {
    }
}
