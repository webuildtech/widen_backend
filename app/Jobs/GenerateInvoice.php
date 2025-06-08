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
        public Payment $payment
    )
    {
    }

    public function handle(): void
    {
        if ($this->payment->paid_at) {
            $invoiceService = new InvoiceService();
            $invoiceService->generate($this->payment);

            $this->payment->refresh();

            $mailable = match ($this->payment->paymentable_type) {
                'reservation' => new ReservationPaidMail($this->payment),
                'plan' => new PlanSubscribeMail($this->payment, $this->payment->renew),
                default => new BalanceTopUpMail($this->payment),
            };

            Mail::queue($mailable);
        }
    }
}
