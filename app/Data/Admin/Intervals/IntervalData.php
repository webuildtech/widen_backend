<?php

namespace App\Data\Admin\Intervals;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\LoadRelation;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class IntervalData extends Data
{
    public function __construct(
        public int        $id,

        public string     $name,

        public ?string    $inside_name,

        public Carbon     $date_from,

        public Carbon     $date_to,

        /** @var Collection<int, IntervalPriceData> */
        #[LoadRelation]
        public Collection $prices
    )
    {
    }
}
