<?php

namespace App\Data\Admin\Groups;

use App\Data\Admin\Plans\PlanSelectOptionData;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\LoadRelation;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GroupListData extends Data
{
    public function __construct(
        public int                       $id,

        public string                    $name,

        #[LoadRelation]
        public PlanSelectOptionData|null $plan,

        public Carbon                    $updated_at,
    )
    {
    }
}
