<?php

namespace App\Data\Admin\Subscriptions;

use App\Data\Admin\Plans\PlanSelectOptionData;
use App\Data\Admin\Users\UserSelectOptionData;
use Carbon\Carbon;
use LucasDotVin\Soulbscription\Models\Subscription;
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

        public string                $periodicity_type,

        #[LoadRelation]
        public ?UserSelectOptionData $subscriber,

        public Carbon                $started_at,

        public Carbon                $expired_at,

        public bool                  $is_overdue,
    )
    {
    }

    public static function fromModel(Subscription $subscription): self
    {
        return new self(
            $subscription->id,
            $subscription->plan ? PlanSelectOptionData::from($subscription->plan->plan) : null,
            $subscription->plan->periodicity_type,
            $subscription->subscriber ? UserSelectOptionData::from($subscription->subscriber) : null,
            $subscription->started_at,
            $subscription->expired_at,
            $subscription->is_overdue,
        );
    }
}
