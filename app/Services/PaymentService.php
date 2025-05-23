<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Jobs\CheckRefundSlots;
use App\Jobs\GenerateInvoice;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Reservation;
use App\Models\User;
use LucasDotVin\Soulbscription\Models\FeatureConsumption;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Facades\Pdf;

class PaymentService
{
    public function createFromPlan(Plan $plan, User $user, bool $renew = false): Payment
    {
        $priceDetails = applyDiscountAndCalculatePriceDetails($plan->price, $user->discount_on_everything);

        $paidAmountFromBalance = $user->getDeductedAmount($priceDetails->price_with_vat);
        $user->deductBalance($paidAmountFromBalance);

        $payment = $plan->payment()->create([
            'user_id' => $user->id,
            'renew' => $renew,
            'price' => $priceDetails->price,
            'vat' => $priceDetails->vat,
            'discount' => $priceDetails->discount,
            'price_with_vat' => $priceDetails->price_with_vat,
            'paid_amount' => $priceDetails->price_with_vat - $paidAmountFromBalance,
            'paid_amount_from_balance' => $paidAmountFromBalance
        ]);

        return $payment;
    }

    public function createFromReservation(Reservation $reservation, User $user = null): Payment
    {
        $paidAmountFromBalance = 0;

        if ($user) {
            $paidAmountFromBalance = $user->getDeductedAmount($reservation->price_with_vat);
            $user->deductBalance($paidAmountFromBalance);
        }

        $payment = $reservation->payment()->create([
            'user_id' => $reservation->user_id,
            'discount' => $reservation->discount,
            'price' => $reservation->price,
            'vat' => $reservation->vat,
            'price_with_vat' => $reservation->price_with_vat,
            'paid_amount' => $reservation->price_with_vat - $paidAmountFromBalance,
            'paid_amount_from_balance' => $paidAmountFromBalance
        ]);

        return $payment;
    }

    public function createFromAmount(float $amount, User $user): Payment
    {
        $vat = round($amount - ($amount / 1.21), 2);

        $payment = Payment::create([
            'user_id' => $user->id,
            'price' => $amount - $vat,
            'vat' => $vat,
            'price_with_vat' => $amount,
            'paid_amount' => $amount,
        ]);

        return $payment;
    }

    public function approve(Payment $payment): Payment
    {
        if ($payment->status === PaymentStatus::PAID->value) {
            return $payment;
        }

        $payment->update(['status' => PaymentStatus::PAID->value, 'paid_at' => now()]);

        match ($payment->paymentable_type) {
            'plan' => $this->handlePlanPayment($payment),
            'reservation' => $this->handleReservationPayment($payment),
            default => $this->handleDefaultPayment($payment),
        };

        GenerateInvoice::dispatch($payment);

        return $payment;
    }

    public function cancel(Payment $payment, PaymentStatus $status): Payment
    {
        if ($payment->status !== PaymentStatus::PENDING->value) {
            return $payment;
        }

        $payment->update(['status' => $status->value]);

        $payment->user?->addBalance($payment->paid_amount_from_balance);

        if ($payment->paymentable_type === 'reservation') {
            $reservation = $payment->paymentable;

            if ($reservation->feature_consumption_id) {
               FeatureConsumption::where('id', $reservation->feature_consumption_id)->delete();
            }

            $reservation->slots()->delete();
            $reservation->times()->delete();
            $reservation->delete();
        }

        return $payment;
    }

    private function handlePlanPayment(Payment $payment): void
    {
        $user = $payment->user;

        $subscription = $user->subscription ?? $user->lastSubscription();

        if (!$subscription || ($subscription->is_overdue && $subscription->canceled_at)) {
            $user->subscribeTo($payment->paymentable);
        } else {
            if ($payment->paymentable_id === $subscription->plan_id) {
                $subscription->renew();
            } else {
                $user->addBalance($payment->price_with_vat);

                $priceDetails = applyDiscountAndCalculatePriceDetails($payment->paid_amount, 0);

                $payment->update([
                    'paymentable_id' => null,
                    'paymentable_type' => null,
                    'discount' => 0,
                    'paid_amount_from_balance' => 0,
                    'price' => $priceDetails->price,
                    'vat' => $priceDetails->vat,
                    'price_with_vat' => $priceDetails->price_with_vat,
                ]);
            }
        }
    }

    private function handleReservationPayment(Payment $payment): void
    {
        $payment->paymentable->update(['is_paid' => true]);

        CheckRefundSlots::dispatch($payment->paymentable);
    }

    private function handleDefaultPayment(Payment $payment): void
    {
        $payment->user->addBalance($payment->paid_amount);
    }

    public function generateInvoice(Payment $payment): Payment
    {
        if ($payment->invoice_path || floatval($payment->paid_amount) === 0.0) {
            return $payment;
        }

        if (!$payment->invoice_no) {
            $payment->update(['invoice_no' => Payment::whereNotNull('invoice_no')->max('invoice_no') + 1]);
        }

        $path = "/invoices/Septyni Sesi SF - " . $payment->invoice_no . ".pdf";

        Pdf::view('pdfs.invoice', ['payment' => $payment])->disk('local')
            ->withBrowsershot(function (Browsershot $browsershot) {
                $browsershot->setNodeBinary(env('LOCAL_NODE_PATH'))
                    ->setNpmBinary(env('LOCAL_NPM_PATH'))
                    ->noSandbox();
            })
            ->save($path);

        $payment->update(['invoice_path' => $path]);

        return $payment;
    }
}
