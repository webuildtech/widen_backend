<?php

namespace App\Data\Core\DiscountCodes;

use Carbon\CarbonInterface;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

class DiscountLineData extends Data
{
    public function __construct(
        public ?int             $court_type_id = null,
        public ?CarbonInterface $starts_at = null,
    )
    {
    }
}
