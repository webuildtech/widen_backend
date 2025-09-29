<?php

namespace App\Http\Controllers\Admin\Forms;

use App\Data\Admin\Forms\BeginnerFormListData;
use App\Http\Controllers\Controller;
use App\Models\Forms\BeginnerForm;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BeginnerFormController extends Controller
{
    public function index()
    {
        $beginnerForms = QueryBuilder::for(BeginnerForm::class)
            ->allowedSorts([
                'email',
                'phone',
                'first_name',
                'last_name',
                'age',
                'groups',
                'marketing_consent',
                'updated_at'
            ])
            ->allowedFilters([
                'email',
                'phone',
                'first_name',
                'last_name',
                'groups',
                'age',
                AllowedFilter::scope('updated_at_between'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return BeginnerFormListData::collect($beginnerForms);
    }
}
