<?php

namespace App\Data\Admin\Admins;

use App\Enums\AdminRole;
use App\Models\Admin;
use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\References\RouteParameterReference;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AdminUpdateData extends Data
{
    public function __construct(
        #[Max(255)]
        public string|Optional      $first_name,

        #[Max(255)]
        public null|string|Optional $last_name,

        #[Unique(Admin::class, ignore: new RouteParameterReference('admin', 'id'), withoutTrashed: true), Email, Max(255)]
        public string|Optional      $email,

        public AdminRole|Optional   $role,

        #[Max(255)]
        public null|string|Optional $phone,

        #[Min(6), Max(32), Confirmed]
        public string|Optional      $password,
    )
    {
    }
}
