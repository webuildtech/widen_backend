<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Invoices\InvoiceExportData;
use App\Data\Admin\Invoices\InvoiceListData;
use App\Exports\InvoicesExport;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Enums\FilterOperator;
use Spatie\QueryBuilder\QueryBuilder;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = QueryBuilder::for(Invoice::class)
            ->with('owner')
            ->defaultSort('-date')
            ->allowedSorts([
                'number',
                'date',
                'owner_type',
                'price',
                'vat',
                'price_with_vat',
                'updated_at'
            ])
            ->allowedFilters([
                'number',
                'owner.first_name',
                'owner.last_name',
                'owner.email',
                'owner.phone',
                AllowedFilter::operator('price_from', FilterOperator::GREATER_THAN_OR_EQUAL, 'and', 'price_with_vat'),
                AllowedFilter::operator('price_to', FilterOperator::LESS_THAN_OR_EQUAL, 'and', 'price_with_vat'),
                AllowedFilter::scope('date_between', 'invoice_date_between'),
                AllowedFilter::scope('updated_at_between'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return InvoiceListData::collect($invoices);
    }

    public function download(Invoice $invoice)
    {
        if (!$invoice->path) {
            return response()->json(['message' => __('invoices.download.not_allowed')], 403);
        }

        return response()->download(storage_path('app' . $invoice->path));
    }

    public function export(InvoiceExportData $dto)
    {
        $filename = sprintf('saskaitos_%s_%s.xlsx', $dto->date_from->format('Ymd'), $dto->date_to->format('Ymd'));

        return Excel::download(new InvoicesExport($dto->date_from, $dto->date_to), $filename);
    }
}
