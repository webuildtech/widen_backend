<?php

namespace App\Data\Admin\Users;

use App\Models\User;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\RequiredIf;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class UserStoreData extends Data
{
    public function __construct(
        #[Max(255)]
        public string               $first_name,

        #[Max(255)]
        public null|string|Optional $last_name,

        #[Unique(User::class, column: 'email'), Email, Max(255)]
        public string               $email,

//        #[Min(-999999.99), Max(999999.99)]
//        public float|Optional       $balance,

        #[Min(0), Max(999999.99)]
        public float|Optional       $overdraft_limit,

        #[Min(0), Max(100)]
        public float|Optional       $discount_on_everything,

        public null|Carbon|Optional $birthday,

        #[Max(255)]
        public null|string|Optional $phone,

        public bool|Optional        $is_company,

        #[RequiredIf('is_company', true), Rule(['max:255'])]
        public null|string|Optional $company_name,

        #[RequiredIf('is_company', true), Rule(['max:255'])]
        public null|string|Optional $company_code,

        #[Rule(['max:255'])]
        public null|string|Optional $company_vat_code,

        #[RequiredIf('is_company', true), Rule(['max:255'])]
        public null|string|Optional $company_address,

        #[Rule(['max:255'])]
        public null|string|Optional $company_phone,

        public bool|Optional        $agreed_newsletter,

        #[Min(6), Max(32), Confirmed]
        public string               $password,
    )
    {
    }
}
