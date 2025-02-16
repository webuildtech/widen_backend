<?php

namespace App\Data\Admin\Groups;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GroupData extends Data
{
    public function __construct(
        public int    $id,

        public string $name,

        public ?int   $plan_id,

        /** @var array<int> */
        public array  $users_ids,
    )
    {
    }
}
