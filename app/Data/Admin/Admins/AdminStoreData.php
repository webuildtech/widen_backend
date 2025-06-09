<?php

namespace App\Data\Admin\Admins;

use App\Enums\AdminRole;
use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AdminStoreData extends Data
{
    public function __construct(
        #[Max(255)]
        public string               $first_name,

        #[Max(255)]
        public string               $last_name,

        public string               $email,

        public AdminRole            $role,

        #[Max(255)]
        public null|string|Optional $phone,

        #[Min(6), Max(32), Confirmed]
        public string               $password,
    )
    {
    }
}
