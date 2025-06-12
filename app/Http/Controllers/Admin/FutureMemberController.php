<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\FutureMembers\FutureMemberListData;
use App\Http\Controllers\Controller;
use App\Models\FutureMember;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FutureMemberController extends Controller
{
    public function index()
    {
        $futureMembers = QueryBuilder::for(FutureMember::class)
            ->allowedSorts([
                'email',
                'phone',
                'first_name',
                'last_name',
                'services',
                'days',
                'times',
                'plan',
                'updated_at'
            ])
            ->allowedFilters([
                'email',
                'phone',
                'first_name',
                'last_name',
                'services',
                'days',
                'times',
                'plan',
                AllowedFilter::scope('updated_at_between'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return FutureMemberListData::collect($futureMembers);
    }
}
