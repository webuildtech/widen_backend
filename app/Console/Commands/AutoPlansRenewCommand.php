<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatus;
use App\Services\Payments\PaymentService;
use Illuminate\Console\Command;
use Log;
use LucasDotVin\Soulbscription\Models\Subscription;

class AutoPlansRenewCommand extends Command
{
    protected $signature = 'app:auto-plans-renew-command';

    public function __construct(
        protected PaymentService $paymentService,
    )
    {
        parent::__construct();
    }

    public function handle(): void
    {
        Subscription::onlyExpired()
            ->notCanceled()
            ->whereDate('expired_at', now()->subDay())
            ->get()
            ->each(function (Subscription $subscription) {
                $user = $subscription->subscriber;

                $priceDetails = applyDiscountAndCalculatePriceDetails($subscription->plan->price, $user->discount_on_everything);

                if ($user->balance >= $priceDetails->price_with_vat) {
                    $payment = $this->paymentService->createFromPlanPrice($subscription->plan, $user);

                    if ($payment->paid_amount > 0) {
                        $this->paymentService->cancel($payment, PaymentStatus::CANCELLED);
                    } else {
                        $this->paymentService->approve($payment);
                    }

                    Log::info('Plan auto renewed user id: ' . $user->id, ['payment' => $payment->toArray()]);
                }
            });
    }
}
