<?php

namespace App\Data\Admin\Availability;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\References\FieldReference;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class StatsFilterData extends Data
{
    public function __construct(
        #[Exists('court_types', 'id', withoutTrashed: true)]
        public int|Optional    $court_type_id,

        #[Date]
        public Carbon          $date_from,

        #[Date, AfterOrEqual(new FieldReference('date_from'))]
        public Carbon|Optional $date_to,
    )
    {
    }
}
