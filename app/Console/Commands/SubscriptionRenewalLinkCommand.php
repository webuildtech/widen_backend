<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionRenewalLinkMail;
use App\Models\Subscription;
use App\Services\Payments\PaymentService;
use App\Services\SubscriptionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SubscriptionRenewalLinkCommand extends Command
{
    private const DAYS_BEFORE_EXPIRY = [7, 1];

    protected $signature = 'app:subscription-renewal-link-command';

    public function __construct(
        protected PaymentService      $paymentService,
        protected SubscriptionService $subscriptionService,
    )
    {
        parent::__construct();
    }

    public function handle(): void
    {
        foreach (self::DAYS_BEFORE_EXPIRY as $days) {
            Subscription::notCanceled()
                ->whereDate('expired_at', now()->addDays($days))
                ->with(['plan.plan', 'subscriber'])
                ->get()
                ->filter(fn(Subscription $subscription) => $subscription->paysByLink())
                ->each(fn(Subscription $subscription) => Mail::queue(new SubscriptionRenewalLinkMail(
                    $subscription,
                    $this->paymentService->planPriceForUser($subscription->plan, $subscription->subscriber),
                    $this->subscriptionService->renewalUrl($subscription),
                )));
        }
    }
}
