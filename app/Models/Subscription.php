<?php

namespace App\Models;

use App\Enums\SubscriptionRenewalMethod;
use App\Models\Concerns\HasDateScopes;
use App\Models\Concerns\HasSubscriptionScopes;
use LucasDotVin\Soulbscription\Models\Subscription as SubscriptionBase;

/**
 * @mixin IdeHelperSubscription
 */
class Subscription extends SubscriptionBase
{
    use HasDateScopes;
    use HasSubscriptionScopes;

    /**
     * Soulbscription declares canceled_at through $dates, which Laravel no longer reads, so it is cast here.
     * Its other date columns already come cast from the Starts, ExpiresAndHasGraceDays and Suppresses traits.
     */
    protected function casts(): array
    {
        return [
            'canceled_at' => 'datetime',
        ];
    }

    public function paysByLink(): bool
    {
        return $this->plan?->price > 0
            && $this->subscriber?->subscription_renewal_method === SubscriptionRenewalMethod::LINK;
    }
}
