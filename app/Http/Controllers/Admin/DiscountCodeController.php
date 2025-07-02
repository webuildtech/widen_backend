<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\DiscountCodes\DiscountCodeData;
use App\Data\Admin\DiscountCodes\DiscountCodeStoreData;
use App\Data\Admin\DiscountCodes\DiscountCodeListData;
use App\Data\Admin\DiscountCodes\DiscountCodeUpdateData;
use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Enums\FilterOperator;
use Spatie\QueryBuilder\QueryBuilder;

class DiscountCodeController extends Controller
{
    public function index()
    {
        $discountCodes = QueryBuilder::for(DiscountCode::class)
            ->allowedSorts([
                'name',
                'is_active',
                'code',
                'type',
                'value',
                'usage_limit',
                'used',
                'date_from',
                'date_to',
                'updated_at'
            ])
            ->allowedFilters([
                'name',
                'code',
                AllowedFilter::operator('value_from', FilterOperator::GREATER_THAN_OR_EQUAL, 'and', 'value'),
                AllowedFilter::operator('value_to', FilterOperator::LESS_THAN_OR_EQUAL, 'and', 'value'),
                AllowedFilter::scope('date_from_between'),
                AllowedFilter::scope('date_to_between'),
                AllowedFilter::scope('updated_at_between'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return DiscountCodeListData::collect($discountCodes);
    }

    public function store(DiscountCodeStoreData $data): DiscountCodeData
    {
        $discountCode = DiscountCode::create($data->all());

        return DiscountCodeData::from($discountCode->refresh());
    }

    public function show(DiscountCode $discountCode): DiscountCodeData
    {
        return DiscountCodeData::from($discountCode);
    }

    public function update(DiscountCodeUpdateData $data, DiscountCode $discountCode): DiscountCodeData
    {
        $discountCode->update($data->all());

        return DiscountCodeData::from($discountCode->refresh());
    }

    public function destroy(DiscountCode $discountCode): array
    {
        $discountCode->delete();

        return [];
    }
}
