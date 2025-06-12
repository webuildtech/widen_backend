<?php

namespace App\Data\Admin\FutureMembers;

use App\Models\FutureMember;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class FutureMemberListData extends Data
{
    public function __construct(
        public string      $email,

        public string      $first_name,

        public string      $last_name,

        public string|null $phone,

        public string      $services,

        public string|null $days,

        public string|null $times,

        public string|null $plan,
    )
    {
    }

    public static function fromModel(FutureMember $futureMember): self
    {
        return new self(
            $futureMember->email,
            $futureMember->first_name,
            $futureMember->last_name,
            $futureMember->phone,
            implode(', ', $futureMember->services),
            $futureMember->days ? implode(', ', $futureMember->days) : null,
            $futureMember->times ? implode(', ', $futureMember->times) : null,
            $futureMember->plan,
        );
    }
}
