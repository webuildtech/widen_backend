<?php

namespace App\Data\User\Forms;

use Spatie\LaravelData\Attributes\Validation\Accepted;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CompanyFormStoreData extends Data
{
    public function __construct(
        #[Max(255)]
        public string $company_name,

        #[Max(255)]
        public string $user,

        #[Max(255)]
        public string $phone,

        #[Email, Max(255)]
        public string $email,

        public bool   $marketing_consent,

        #[Accepted]
        public bool   $service_consent,
    )
    {
    }
}
