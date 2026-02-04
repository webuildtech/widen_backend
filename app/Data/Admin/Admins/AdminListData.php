<?php

namespace App\Data\Admin\Admins;

use App\Enums\AdminRole;
use App\Enums\Locale;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AdminListData extends Data
{
    public function __construct(
        public int       $id,

        public string    $first_name,

        public string    $last_name,

        public AdminRole $role,

        public string    $email,

        public Locale    $locale,

        public ?string   $phone,

        public Carbon    $updated_at,
    )
    {
    }
}
