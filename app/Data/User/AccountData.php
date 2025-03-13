<?php

namespace App\Data\User;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AccountData extends Data
{
    public function __construct(
        public string  $email,

        public string  $first_name,

        public ?string $last_name,

        public ?Carbon $birthday,

        public ?string $phone,

        public float   $balance,

        public int     $cancel_before
    )
    {
    }
}
