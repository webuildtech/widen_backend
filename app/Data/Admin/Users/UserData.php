<?php

namespace App\Data\Admin\Users;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class UserData extends Data
{
    public function __construct(
        public int     $id,

        public string  $first_name,

        public ?string $last_name,

        public string  $email,

//        public float   $balance,

        public float   $discount_on_everything,

        public ?Carbon $birthday,

        public ?string $phone,

        public bool    $is_company,

        public ?string $company_name,

        public ?string $company_code,

        public ?string $company_vat_code,

        public ?string $company_address,

        public ?string $company_phone,

        public bool    $agreed_newsletter
    )
    {
    }
}
