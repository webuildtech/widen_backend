<?php

namespace App\Data\Admin\Users;

use App\Data\Admin\Admins\AdminSelectOptionData;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class UserBalanceEntryListData extends Data
{
    public function __construct(
        public float                 $amount,

        public AdminSelectOptionData $admin,

        public ?UserSelectOptionData  $user,

        public float                 $before_balance,

        public float                 $after_balance,

        public Carbon                $created_at
    )
    {
    }
}
