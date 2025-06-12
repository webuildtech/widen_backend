<?php

namespace App\Data\User\Account;

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

        public float   $discount_on_everything,

        public bool    $is_company,

        public ?string $company_name,

        public ?string $company_code,

        public ?string $company_vat_code,

        public ?string $company_address,

        public ?string $company_phone,
    )
    {
    }
}
