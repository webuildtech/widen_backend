<?php

namespace App\Data\Admin\Groups;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class UpdateGroupData extends Data
{
    public function __construct(
        #[Rule(['max:255'])]
        public string|Optional     $name,

        #[Exists('plans', 'id', withoutTrashed: true)]
        public int|null|Optional   $plan_id,

        /** @var array<int> */
        public array|Optional|null $users_ids,
    )
    {
        if ($this->users_ids === null) {
            $this->users_ids = [];
        }
    }

    public static function rules(): array
    {
        return [
            'users_ids.*' => ['required', new Exists('users', 'id', withoutTrashed: true)],
        ];
    }
}
