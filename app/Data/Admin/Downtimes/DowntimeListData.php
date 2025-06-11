<?php

namespace App\Data\Admin\Downtimes;

use App\Data\Admin\Courts\CourtSelectOptionData;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DowntimeListData extends Data
{
    public function __construct(
        public int                   $id,

        public CourtSelectOptionData $court,

        public string                $date_from,

        public string                $date_to,

        public string                $start_time,

        public string                $end_time,

        public ?string               $comment,

        public Carbon                $updated_at,
    )
    {
    }
}
