<?php

namespace App\Data\User\Account;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;
use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AccountPasswordChangeData extends Data
{
    public function __construct(
        #[Min(6), Max(32)]
        public string $old_password,

        #[Min(6), Max(32), Confirmed]
        public string $password,
    )
    {
    }

    public static function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $user = auth()->user();
            $password = $validator->getData()['old_password'] ?? null;

            if (!$password || !Hash::check($password, $user->password)) {
                $validator->errors()->add('old_password', 'Pateiktas slaptažodis neteisingas.');
            }
        });
    }
}
