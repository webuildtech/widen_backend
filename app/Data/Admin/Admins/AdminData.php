<?php

namespace App\Data\Admin\Admins;

use App\Enums\AdminRole;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AdminData extends Data
{
    public function __construct(
        public int       $id,

        public string    $first_name,

        public ?string   $last_name,

        public AdminRole $role,

        public string    $email,

        public ?string   $phone,
    )
    {
    }
}
