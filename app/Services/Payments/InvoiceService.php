<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Facades\Pdf;

class InvoiceService
{
    public function generate(Payment $payment): void
    {
        if ($payment->invoice_path || floatval($payment->paid_amount) === 0.0) return;

        if (!$payment->invoice_no) {
            $payment->update([
                'invoice_no' => Payment::whereNotNull('invoice_no')->max('invoice_no') + 1
            ]);
        }

        $path = "/invoices/Septyni Sesi SF - {$payment->invoice_no}.pdf";

        Pdf::view('pdfs.invoice', ['payment' => $payment])
            ->disk('local')
            ->withBrowsershot(function (Browsershot $browsershot) {
                $browsershot->setNodeBinary(env('LOCAL_NODE_PATH'))
                    ->setNpmBinary(env('LOCAL_NPM_PATH'))
                    ->noSandbox();
            })
            ->save($path);

        $payment->update(['invoice_path' => $path]);
    }
}
