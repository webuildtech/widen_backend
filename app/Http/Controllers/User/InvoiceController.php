<?php

namespace App\Http\Controllers\User;

use App\Data\User\Invoices\InvoiceListData;
use App\Http\Controllers\Controller;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = auth()->user()->invoices()
            ->orderBy('date', 'desc')
            ->get();

        return InvoiceListData::collect($invoices);
    }

    public function download(Invoice $invoice)
    {
        $user = auth()->user();

        if ($user->id !== $invoice->owner_id || $invoice->owner_type !== 'user' || !$invoice->path) {
            return response()->json(['error' => 'Veiksmas negalimas!'], 403);
        }

        return response()->download(storage_path('app' . $invoice->path));
    }
}
