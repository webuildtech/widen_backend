<?php

namespace App\Services\Payments;

use App\Models\Guest;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Facades\Pdf;

class InvoiceService
{
    public function create(User|Guest $owner, Carbon $date, float $priceWithVat): Invoice
    {
        $priceDetails = applyDiscountAndCalculatePriceDetails($priceWithVat);;

        $invoice = $owner->invoices()->create([
            'date' => $date,
            'number' => Invoice::max('number') + 1,
            'price' => $priceDetails->price,
            'vat' => $priceDetails->vat,
            'price_with_vat' => $priceDetails->price_with_vat,
        ]);

        $path = "/invoices/WIDEN Arena SF - {$invoice->number}.pdf";

        Pdf::view('pdfs.invoice', ['invoice' => $invoice])
            ->disk('local')
            ->withBrowsershot(function (Browsershot $browsershot) {
                $browsershot->setNodeBinary(env('LOCAL_NODE_PATH'))
                    ->setNpmBinary(env('LOCAL_NPM_PATH'))
                    ->noSandbox();
            })
            ->save($path);

        $invoice->update(['path' => $path]);

        return $invoice->refresh();
    }
}
