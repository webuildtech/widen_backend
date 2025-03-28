<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatus;
use App\Services\PaymentService;
use Illuminate\Console\Command;
use Log;
use LucasDotVin\Soulbscription\Models\Subscription;

class AutoPlansRenewCommand extends Command
{
    protected $signature = 'app:auto-plans-renew-command';

    public function handle(): void
    {
        $paymentService = new PaymentService();

        Subscription::onlyExpired()
            ->notCanceled()
            ->whereDate('expired_at', now()->subDay())
            ->get()
            ->each(function (Subscription $subscription) use ($paymentService) {
                $user = $subscription->subscriber;

                $priceDetails = applyDiscountAndCalculatePriceDetails($subscription->plan->price, $user->discount_on_everything);

                if ($user->balance >= $priceDetails->price_with_vat) {
                    $payment = $paymentService->createFromPlan($subscription->plan, $user);

                    if ($payment->paid_amount > 0) {
                        $paymentService->cancel($payment, PaymentStatus::CANCELLED);
                    } else {
                        $paymentService->approve($payment);
                    }

                    Log::info('Plan auto renewed user id: ' . $user->id, ['payment' => $payment->toArray()]);
                }
            });
    }
}
