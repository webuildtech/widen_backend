<?php

namespace App\Data\User\Auth;

use App\Models\User;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Accepted;
use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class RegisterData extends Data
{
    public function __construct(
        #[Unique(User::class, column: 'email'), Email, Max(255)]
        public string        $email,

        #[Max(255)]
        public string        $first_name,

        #[Max(255)]
        public string        $last_name,

        public Carbon        $birthday,

        public string        $phone,

        #[Min(6), Max(32), Confirmed]
        public string        $password,

        #[Accepted]
        public bool          $agreed_terms,

        public bool|Optional $agreed_newsletter,
    )
    {
    }
}
