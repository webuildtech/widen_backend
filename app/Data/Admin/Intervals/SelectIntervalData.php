<?php

namespace App\Data\Admin\Intervals;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class SelectIntervalData extends Data
{
    public function __construct(
        public int        $id,

        public string     $name,

        public ?string    $inside_name,

        public Carbon     $date_from,

        public Carbon     $date_to,
    )
    {
    }
}
