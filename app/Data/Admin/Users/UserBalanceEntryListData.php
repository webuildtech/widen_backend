<?php

namespace App\Data\Admin\Users;

use App\Models\UserBalanceEntry;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class UserBalanceEntryListData extends Data
{
    public function __construct(
        public float $amount,

        public string $admin,

        public float $before_balance,

        public float $after_balance,

        public Carbon $created_at
    )
    {
    }

    public static function fromModel(UserBalanceEntry $entry): self
    {
        return new self(
            $entry->amount,
            $entry->admin->full_name,
            $entry->before_balance,
            $entry->after_balance,
            $entry->created_at,
        );
    }
}
