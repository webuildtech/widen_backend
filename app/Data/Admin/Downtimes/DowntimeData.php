<?php

namespace App\Data\Admin\Downtimes;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DowntimeData extends Data
{
    public function __construct(
        public int     $id,

        public int     $court_id,

        public Carbon  $date_from,

        public Carbon  $date_to,

        public string  $start_time,

        public string  $end_time,

        public ?string $comment
    )
    {
    }
}
