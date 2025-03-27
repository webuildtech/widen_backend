<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateInvoice implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Payment $payment
    ) {}

    public function handle(): void
    {
        if ($this->payment->paid_at) {
            $paymentService = new PaymentService();

            $this->payment = $paymentService->generateInvoice($this->payment);
        }
    }
}
