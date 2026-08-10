<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Subscriptions\SubscriptionListData;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = QueryBuilder::for(Subscription::class)
            ->withoutGlobalScopes()
            ->with('plan.plan')
            ->visible()
            ->defaultSort('expired_at')
            ->allowedSorts([
                'subscriber_id',
                'plan_id',
                'started_at',
                'expired_at',
                'canceled_at',
            ])
            ->allowedFilters([
                AllowedFilter::exact('plan_id', 'plan.plan_id'),
                AllowedFilter::exact('periodicity_type', 'plan.periodicity_type'),
                AllowedFilter::scope('subscriber.first_name', 'subscriber_first_name'),
                AllowedFilter::scope('subscriber.last_name', 'subscriber_last_name'),
                AllowedFilter::scope('subscriber.email', 'subscriber_email'),
                AllowedFilter::scope('subscriber.phone', 'subscriber_phone'),
                AllowedFilter::scope('started_at_between'),
                AllowedFilter::scope('expired_at_between'),
                AllowedFilter::scope('canceled_at_between'),
                AllowedFilter::scope('status'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());


        return SubscriptionListData::collect($subscriptions);
    }

    public function cancel(int $subscription): array
    {
        $subscription = Subscription::query()
            ->withoutGlobalScopes()
            ->visible()
            ->findOrFail($subscription);

        if (!$subscription->canceled_at) {
            // Canceling alone only stops the auto renewal, so the plan is suppressed as well to end it right away.
            // Suppressing (instead of moving expired_at) keeps the paid-until date visible in the list.
            $subscription->cancel();
            $subscription->suppress();
        }

        return [];
    }
}
