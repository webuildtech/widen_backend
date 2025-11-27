<?php

namespace App\Data\Admin\Reports;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\References\FieldReference;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class UserBalancesExportReportData extends Data
{
    public function __construct(
        #[Date]
        public Carbon $date_from,

        #[Date, AfterOrEqual(new FieldReference('date_from'))]
        public Carbon $date_to,

        public bool   $hide_zero_balances,

        public bool   $hide_zero_invoices
    )
    {
    }
}
