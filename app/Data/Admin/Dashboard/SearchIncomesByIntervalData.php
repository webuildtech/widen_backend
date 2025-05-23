<?php

namespace App\Data\Admin\Dashboard;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\References\FieldReference;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SearchIncomesByIntervalData extends Data
{
    public function __construct(
        #[Date]
        public Carbon          $date_from,

        #[Date, AfterOrEqual(new FieldReference('date_from'))]
        public Carbon|Optional $date_to,
    )
    {
    }
}
