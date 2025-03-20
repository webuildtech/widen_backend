<?php

namespace App\Jobs;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessPaymentCallback implements ShouldQueue
{
    use Queueable;

    protected PaymentService $paymentService;

    public function __construct(
        public array $values,
    )
    {
        $this->onQueue('payments');
        $this->paymentService = new PaymentService();
    }

    public function handle(): void
    {
        $payment = Payment::whereTransactionId($this->values['transaction'])->first();

        match ($this->values['status']) {
            'COMPLETED' => $this->paymentService->approve($payment),
            'CANCELLED' => $this->paymentService->cancel($payment, PaymentStatus::CANCELLED),
            'EXPIRED' => $this->paymentService->cancel($payment, PaymentStatus::EXPIRED),
            default => $payment
        };
    }
}
