<?php

namespace App\Data\Admin\Intervals;

use App\Models\Group;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class IntervalPriceGroupData extends Data
{
    public function __construct(
        #[Exists('groups', 'id', withoutTrashed: true)]
        public int   $group_id,

        #[Numeric, Min(0)]
        public float $price,
    )
    {
    }

    public static function fromModel(Group $group): self
    {
        return new self(
            $group->id,
            $group->pivot->price
        );
    }
}
