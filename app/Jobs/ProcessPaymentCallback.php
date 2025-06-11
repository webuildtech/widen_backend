<?php

namespace App\Jobs;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Services\Payments\PaymentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessPaymentCallback implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public array $values,
    )
    {
        $this->onQueue('payments');
    }

    public function handle(PaymentService $paymentService): void
    {
        $payment = Payment::whereTransactionId($this->values['transaction'])->first();

        match ($this->values['status']) {
            'COMPLETED' => $paymentService->approve($payment),
            'CANCELLED' => $paymentService->cancel($payment, PaymentStatus::CANCELLED),
            'EXPIRED' => $paymentService->cancel($payment, PaymentStatus::EXPIRED),
            default => $payment
        };
    }
}
