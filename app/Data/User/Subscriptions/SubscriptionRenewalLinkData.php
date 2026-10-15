<?php

namespace App\Data\User\Subscriptions;

use Spatie\LaravelData\Data;

class SubscriptionRenewalLinkData extends Data
{
    public function __construct(
        public int $until,
    )
    {
    }
}
