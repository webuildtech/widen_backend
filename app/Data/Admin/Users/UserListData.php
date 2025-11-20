<?php

namespace App\Data\Admin\Users;

use App\Models\User;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\LoadRelation;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class UserListData extends Data
{
    public function __construct(
        public int     $id,

        public string  $first_name,

        public ?string $last_name,

        public string  $email,

        public float   $balance,

        public float   $overdraft_limit,

        public float   $discount_on_everything,

        public ?Carbon $birthday,

        public ?string $phone,

        public bool    $is_company,

        public ?string $company_name,

        public bool    $agreed_newsletter,

        public ?string $plan,

        public Carbon  $updated_at,
    )
    {
    }

    public static function fromModel(User $user): self
    {
        return new self(
            $user->id,
            $user->first_name,
            $user->last_name,
            $user->email,
            $user->balance,
            $user->overdraft_limit,
            $user->discount_on_everything,
            $user->birthday ? Carbon::parse($user->birthday) : null,
            $user->phone,
            $user->is_company,
            $user->company_name,
            $user->agreed_newsletter,
            $user->subscription?->plan?->plan->name,
            $user->updated_at,
        );
    }
}
