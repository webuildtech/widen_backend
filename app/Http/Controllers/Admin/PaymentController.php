<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Payments\PaymentListData;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Enums\FilterOperator;
use Spatie\QueryBuilder\QueryBuilder;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = QueryBuilder::for(Payment::class)
            ->where('status', PaymentStatus::PAID->value)
            ->where(function ($query) {
                $query->where('price', '>', 0)->orWhere('discount', '>', 0);
            })
            ->with('owner')
            ->defaultSort('-paid_at')
            ->allowedSorts([
                'owner_type',
                'paymentable_type',
                'price',
                'discount',
                'vat',
                'price_with_vat',
                'paid_amount_from_balance',
                'paid_amount',
                'paid_at',
                'updated_at'
            ])
            ->allowedFilters([
                'owner.first_name',
                'owner.last_name',
                'owner.email',
                'owner.phone',
                AllowedFilter::operator('price_from', FilterOperator::GREATER_THAN_OR_EQUAL, 'and', 'price_with_vat'),
                AllowedFilter::operator('price_to', FilterOperator::LESS_THAN_OR_EQUAL, 'and', 'price_with_vat'),
                AllowedFilter::operator('paid_amount_from_balance_from', FilterOperator::GREATER_THAN_OR_EQUAL, 'and', 'paid_amount_from_balance'),
                AllowedFilter::operator('paid_amount_from_balance_to', FilterOperator::LESS_THAN_OR_EQUAL, 'and', 'paid_amount_from_balance'),
                AllowedFilter::operator('paid_amount_from', FilterOperator::GREATER_THAN_OR_EQUAL, 'and', 'paid_amount'),
                AllowedFilter::operator('paid_amount_to', FilterOperator::LESS_THAN_OR_EQUAL, 'and', 'paid_amount'),
                AllowedFilter::scope('paid_at_between'),
                AllowedFilter::scope('updated_at_between'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return PaymentListData::collect($payments);
    }
}
