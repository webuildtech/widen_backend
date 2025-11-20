<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Subscriptions\SubscriptionListData;
use App\Http\Controllers\Controller;
use LucasDotVin\Soulbscription\Models\Subscription;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = QueryBuilder::for(Subscription::class)
            ->withoutGlobalScopes()
            ->with('plan.plan')
            ->whereNull(['deleted_at', 'suppressed_at'])
            ->defaultSort('expired_at')
            ->allowedSorts([
                'subscriber_id',
                'plan_id',
                'started_at',
                'expired_at',
            ])
            ->allowedFilters([
                AllowedFilter::exact('plan_id', 'plan.plan_id'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());


        return SubscriptionListData::collect($subscriptions);
    }
}
