<?php

namespace App\Data\User\Account;

use App\Models\User;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\RequiredIf;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class UpdateAccountData extends Data
{
    public function __construct(
        #[Max(255)]
        public string|Optional      $first_name,

        #[Max(255)]
        public string|Optional      $last_name,

        public string|Optional      $email,

        public Carbon|Optional      $birthday,

        #[Max(255)]
        public string|Optional      $phone,

        public bool|Optional        $is_company,

        #[RequiredIf('is_company', 'true'), Rule(['max:255'])]
        public null|string|Optional $company_name,

        #[RequiredIf('is_company', 'true'), Rule(['max:255'])]
        public null|string|Optional $company_code,

        #[Rule(['max:255'])]
        public null|string|Optional $company_vat_code,

        #[RequiredIf('is_company', 'true'), Rule(['max:255'])]
        public null|string|Optional $company_address,

        #[Rule(['max:255'])]
        public null|string|Optional $company_phone,
    )
    {
        if (!$this->is_company) {
            $this->company_name = null;
            $this->company_code = null;
            $this->company_vat_code = null;
            $this->company_address = null;
            $this->company_phone = null;
        }
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'email' => [
                'sometimes',
                new Unique(User::class, ignore: auth()->user()->id, withoutTrashed: true),
                new Email(),
                new Max(255)
            ],
        ];
    }
}
