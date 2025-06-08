<?php

namespace App\Data\Admin\Owners;

use App\Models\Guest;
use App\Models\User;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class OwnerData extends Data
{
    public function __construct(
        public string $full_name,

        public string $email,

        public string $phone,
    )
    {
    }

    public static function fromModel(Guest|User $owner): self
    {
        return new self(
            $owner->full_name,
            $owner->email,
            $owner->phone,
        );
    }
}
