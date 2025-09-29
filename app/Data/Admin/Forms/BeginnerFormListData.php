<?php

namespace App\Data\Admin\Forms;

use App\Models\Forms\BeginnerForm;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class BeginnerFormListData extends Data
{
    public function __construct(
        public string $email,

        public string $first_name,

        public string $last_name,

        public string $phone,

        public int    $age,

        public string $groups,

        public bool   $marketing_consent,

        public Carbon $updated_at,
    )
    {
    }

    public static function fromModel(BeginnerForm $beginnerForm): self
    {
        return new self(
            $beginnerForm->email,
            $beginnerForm->first_name,
            $beginnerForm->last_name,
            $beginnerForm->phone,
            $beginnerForm->age,
            implode(', ', $beginnerForm->groups),
            $beginnerForm->marketing_consent,
            $beginnerForm->updated_at,
        );
    }
}
