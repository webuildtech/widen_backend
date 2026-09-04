<?php

namespace App\Data\Admin\Dashboard;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PlanSubscriptionMetricData extends Data
{
    public function __construct(
        public int    $plan_id,

        public string $name,

        public string $type,

        public int    $total,

        public int    $monthly,

        public int    $yearly,
    )
    {
    }
}
