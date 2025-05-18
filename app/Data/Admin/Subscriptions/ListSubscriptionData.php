<?php

namespace App\Data\Admin\Subscriptions;

use App\Data\Admin\Plans\SelectPlanData;
use App\Data\Admin\Users\SelectUserData;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\LoadRelation;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ListSubscriptionData extends Data
{
    public function __construct(
        public int             $id,

        #[LoadRelation]
        public ?SelectPlanData $plan,

        #[LoadRelation]
        public ?SelectUserData $subscriber,

        public Carbon          $started_at,

        public Carbon          $expired_at,

        public bool            $is_overdue,
    )
    {
    }
}
