<?php

namespace App\Jobs;

use App\Mail\BalanceTopUpMail;
use App\Mail\PlanSubscribeMail;
use App\Mail\ReservationPaidMail;
use App\Models\Payment;
use App\Services\Payments\InvoiceService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class GenerateInvoice implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int               $paymentId,
        protected InvoiceService $invoiceService
    )
    {
    }

    public function handle(): void
    {
        $payment = Payment::findOrFail($this->paymentId);

        if ($payment->paid_at) {
            $this->invoiceService->generate($payment);
            $payment->refresh();

            $mailable = match ($payment->paymentable_type) {
                'reservation' => new ReservationPaidMail($payment),
                'plan' => new PlanSubscribeMail($payment, $payment->renew),
                default => new BalanceTopUpMail($payment),
            };

            Mail::queue($mailable);
        }
    }
}
