<?php

namespace App\Data\Admin\Courts;

use App\Enums\CourtType;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Image;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class StoreCourtData extends Data
{
    public function __construct(
        #[Rule(['max:255'])]
        public string                $name,

        #[Rule(['max:255'])]
        public ?string               $inside_name,

        public ?string               $description,

        public CourtType             $type,

        public bool|Optional         $active,

        #[Image, Max(20480)]
        public UploadedFile|Optional $logoFile,
    )
    {
    }
}
